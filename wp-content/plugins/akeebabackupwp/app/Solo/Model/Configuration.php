<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

namespace Solo\Model;

use Akeeba\Engine\Archiver\Directftp;
use Akeeba\Engine\Archiver\Directsftp;
use Akeeba\Engine\Factory;
use Akeeba\Engine\Platform;
use Akeeba\Engine\Util\Transfer\FtpCurl;
use Akeeba\Engine\Util\Transfer\SftpCurl;
use Awf\Mvc\Model;
use Awf\Text\Text;
use RuntimeException;

/**
 * The Model for the Configuration view
 */
class Configuration extends Model
{
	/**
	 * Saves the backup engine configuration to non-volatile storage
	 *
	 * @return  void
	 */
	public function saveEngineConfig()
	{
		$data = $this->getState('engineconfig', array());

		// Forbid stupidly selecting the site's root as the output or temporary directory
		if (array_key_exists('akeeba.basic.output_directory', $data))
		{
			$folder = $data['akeeba.basic.output_directory'];
			$folder = Factory::getFilesystemTools()->translateStockDirs($folder, true, true);

			$check = Factory::getFilesystemTools()->translateStockDirs('[SITEROOT]', true, true);

			if ($check == $folder)
			{
				$this->container->application->enqueueMessage(Text::_('COM_AKEEBA_CONFIG_OUTDIR_ROOT'), 'warning');
				$data['akeeba.basic.output_directory'] = '[DEFAULT_OUTPUT]';
			}
		}

		// Merge it
		$config = Factory::getConfiguration();
		$protectedKeys = $config->getProtectedKeys();
		$config->resetProtectedKeys();
		$config->mergeArray($data, false, false);
		$config->setProtectedKeys($protectedKeys);

		// Save configuration
		Platform::getInstance()->save_configuration();
	}

	/**
	 * Tests an FTP connection and makes sure that we can connect to the server and change to the initial directory
	 *
	 * @return  boolean|array  True on success, a list of errors on failure
	 */
	public function testFTP()
	{
		$config = array(
			'host'    => $this->getState('host'),
			'port'    => $this->getState('port'),
			'user'    => $this->getState('user'),
			'pass'    => $this->getState('pass'),
			'initdir' => $this->getState('initdir'),
			'usessl'  => $this->getState('usessl'),
			'passive' => $this->getState('passive'),
		);

		// Check for bad settings
		if (substr($config['host'], 0, 6) == 'ftp://')
		{
			return Text::_('COM_AKEEBA_CONFIG_FTPTEST_BADPREFIX');
		}

		// Special case for cURL transport
		if ($this->getState('isCurl'))
		{
			try
			{
				$this->testFtpCurl();
			}
			catch (RuntimeException $e)
			{
				return array($e->getMessage());
			}

			return true;
		}

		// Perform the FTP connection test
		$test = new Directftp();
		$test->initialize('', $config);

		$errors = $test->getError();

		if (empty($errors) || $test->connect_ok)
		{
			$result = true;
		}
		else
		{
			$result = $errors;
		}

		return $result;
	}

	/**
	 * Test the connection to a remote FTP server using cURL transport
	 *
	 * @throws  RuntimeException
	 */
	private function testFtpCurl()
	{
		$options = array(
			'host'        => $this->getState('host'),
			'port'        => $this->getState('port'),
			'username'    => $this->getState('user'),
			'password'    => $this->getState('pass'),
			'directory'   => $this->getState('initdir'),
			'usessl'      => $this->getState('usessl'),
			'passive'     => $this->getState('passive'),
			'passive_fix' => $this->getState('passive_mode_workaround'),
		);

		$sftpTransfer = new FtpCurl($options);

		$sftpTransfer->connect();
	}

	/**
	 * Tests an SFTP connection and makes sure that we can connect to the server and change to the initial directory
	 *
	 * @return  boolean|array  True on success, a list of errors on failure
	 */
	public function testSFTP()
	{
		$config = array(
			'host'    => $this->getState('host'),
			'port'    => $this->getState('port'),
			'user'    => $this->getState('user'),
			'pass'    => $this->getState('pass'),
			'privkey' => $this->getState('privkey'),
			'pubkey'  => $this->getState('pubkey'),
			'initdir' => $this->getState('initdir'),
		);

		// Check for bad settings
		if (substr($config['host'], 0, 7) == 'sftp://')
		{
			return Text::_('COM_AKEEBA_CONFIG_SFTPTEST_BADPREFIX');
		}

		// Special case for cURL transport
		if ($this->getState('isCurl'))
		{
			try
			{
				$this->testSftpCurl();
			}
			catch (RuntimeException $e)
			{
				return array($e->getMessage());
			}

			return true;
		}


		// Perform the FTP connection test
		$test = new Directsftp();
		$test->initialize('', $config);
		$errors = $test->getWarnings();

		if (empty($errors) || $test->connect_ok)
		{
			$result = true;
		}
		else
		{
			$result = $errors;
		}

		return $result;
	}

	/**
	 * Test the connection to a remote SFTP server using cURL transport
	 *
	 * @throws  RuntimeException
	 */
	private function testSftpCurl()
	{
		$options = array(
			'host'       => $this->getState('host'),
			'port'       => $this->getState('port'),
			'username'   => $this->getState('user'),
			'password'   => $this->getState('pass'),
			'directory'  => $this->getState('initdir'),
			'privateKey' => $this->getState('privkey'),
			'publicKey'  => $this->getState('pubkey')
		);

		$this->sftpTransfer = new SftpCurl($options);

		$this->sftpTransfer->connect();
	}

	/**
	 * Opens an OAuth window for the selected post-processing engine
	 *
	 * @return  boolean|void  False on failure, no return on success
	 */
	public function dpeOAuthOpen()
	{
		$engine = $this->getState('engine');
		$params = $this->getState('params', array());

		// Get a callback URI for OAuth 2
		$params['callbackURI'] = $this->container->router->route('index.php?view=configuration&task=dpecustomapiraw&engine=' . $engine);

		// Get the Input object
		$params['input'] = $this->input->getData();

		$engineObject = Factory::getPostprocEngine($engine);

		if ($engineObject === false)
		{
			return false;
		}

		$engineObject->oauthOpen($params);
	}

	/**
	 * Runs a custom API call for the selected post-processing engine
	 *
	 * @return  boolean  True on success
	 */
	public function dpeCustomAPICall()
	{
		$engine = $this->getState('engine');
		$method = $this->getState('method');
		$params = $this->getState('params', array());

		// Get the Input object
		$params['input'] = $this->input->getData();

		$engineObject = Factory::getPostprocEngine($engine);

		if ($engineObject === false)
		{
			return false;
		}

		return $engineObject->customApiCall($method, $params);
	}
} 
