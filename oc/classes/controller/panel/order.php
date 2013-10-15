<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_Crud {

	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('id_order','id_user','paymethod','amount','status');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'order';


}