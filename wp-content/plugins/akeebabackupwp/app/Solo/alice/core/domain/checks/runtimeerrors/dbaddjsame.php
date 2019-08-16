<?php
/**
 * @package   akeebabackup
 * @copyright Copyright (c)2006-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

use Akeeba\Engine\Factory;
use Awf\Application\Application;
use Awf\Text\Text;

/**
 * Check if the user added the site database as additional database. Some servers won't allow more than one connection
 * to the same database, causing the backup process to fail
 */
class AliceCoreDomainChecksRuntimeerrorsDbaddjsame extends AliceCoreDomainChecksAbstract
{
	public function __construct($logFile = null)
	{
		parent::__construct(100, 'COM_AKEEBA_ALICE_ANALYZE_RUNTIME_ERRORS_DBADD_JSAME', $logFile);
	}

	public function check()
	{
		$handle  = @fopen($this->logFile, 'r');
		$profile = 0;
		$error   = false;

		if ($handle === false)
		{
			AliceUtilLogger::WriteLog(_AE_LOG_ERROR, $this->checkName . ' Test error, could not open backup log file.');

			return false;
		}

		while (($line = fgets($handle)) !== false)
		{
			$pos = strpos($line, '|Loaded profile');

			if ($pos !== false)
			{
				preg_match('/profile\s+#(\d+)/', $line, $matches);

				if (isset($matches[1]))
				{
					$profile = $matches[1];
				}

				break;
			}
		}

		fclose($handle);

		// Mhm... no profile ID? Something weird happened better stop here and mark the test as skipped
		if ( !$profile)
		{
			$this->setResult(0);
			$this->setErrLangKey('COM_AKEEBA_ALICE_ANALYZE_RUNTIME_ERRORS_DBADD_NO_PROFILE');

			throw new Exception(Text::_('COM_AKEEBA_ALICE_ANALYZE_RUNTIME_ERRORS_DBADD_NO_PROFILE'));
		}

		// Do I have to switch profile?
		$container   = Application::getInstance()->getContainer();
		$session     = $container->segment;
		$cur_profile = $session->get('profile', 'null');

		if ($cur_profile != $profile)
		{
			$session->set('profile', $profile);
		}

		$filters = Factory::getFilters();
		$multidb = $filters->getFilterData('multidb');
		$cmsDB = \Akeeba\Engine\Platform::getInstance()->get_platform_database_options();

		foreach ($multidb as $addDb)
		{
			$options = [
				'host'     => $addDb['host'],
				'user'     => $addDb['username'],
				'password' => $addDb['password'],
				'database' => $addDb['database'],
				'prefix'   => $addDb['prefix'],
			];

			// It's the same database used by Joomla, this could led to errors
			if ($cmsDB == $options)
			{
				$error = true;

				break;
			}
		}

		// If needed set the old profile again
		if ($cur_profile != $profile)
		{
			$session->set('profile', $cur_profile);
		}

		if ($error)
		{
			$this->setResult(-1);
			$this->setErrLangKey('COM_AKEEBA_ALICE_ANALYZE_RUNTIME_ERRORS_DBADD_JSAME_ERROR');

			throw new Exception(Text::_('COM_AKEEBA_ALICE_ANALYZE_RUNTIME_ERRORS_DBADD_JSAME_ERROR'));
		}

		return true;
	}

	public function getSolution()
	{
		// Test skipped? No need to provide a solution
		if ($this->getResult() === 0)
		{
			return '';
		}

		return Text::_('COM_AKEEBA_ALICE_ANALYZE_RUNTIME_ERRORS_DBADD_JSAME_SOLUTION');
	}
}
