<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Affiliate extends Auth_Crud {

    
	
	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('amount','created');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'affiliate';
	

	

}
