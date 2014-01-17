<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Review extends Auth_Crud {

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('update');

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_review','id_order','id_product','id_user','created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'review';


}
