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
 * Checks system requirements ie PHP version, Database version and type, memory limits etc etc
 */
class AliceCoreDomainRequirements extends AliceCoreDomainAbstract
{
	public function __construct()
	{
		parent::__construct(20, 'requirements', Text::_('COM_AKEEBA_ALICE_ANALYZE_REQUIREMENTS'));
	}
}
