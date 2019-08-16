<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

// Protection against direct access
use Awf\Text\Text;

defined('AKEEBAENGINE') or die();

/**
 * Checks for runtime errors, ie Backup Timeout, timeout on post-processing etc etc
 */
class AliceCoreDomainFilesystem extends AliceCoreDomainAbstract
{
	public function __construct()
	{
		parent::__construct(40, 'filesystem', Text::_('COM_AKEEBA_ALICE_ANALYZE_FILESYSTEM'));
	}
}
