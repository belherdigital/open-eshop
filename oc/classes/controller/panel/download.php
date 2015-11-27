<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Download extends Auth_CrudAjax {

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array();

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_download','id_order','id_user','ip_address','created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'download';


    protected $_fields_caption = array( 'id_user'       => array('model'=>'user','caption'=>'email'),
                                         );

    protected $_filter_fields = array(  'id_user' => 'INPUT', 'created'=>'DATE');

    protected $_search_fields = array('ip_address');
}
