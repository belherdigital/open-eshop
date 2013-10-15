<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Config extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('group_name','config_key','config_value');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'config';


}
