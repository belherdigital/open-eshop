<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_License extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('license','license_check_date','active_date','domain','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'license';

}
