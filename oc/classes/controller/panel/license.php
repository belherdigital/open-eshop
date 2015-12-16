<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_License extends Auth_CrudAjax {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('domain','license','id_product','id_user','license_check_date','active_date','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'license';

    protected $_filter_fields = array(  'license_check_date' => 'DATE', 
                                        'active_date'   => 'DATE',
                                        'id_product'    => array('type'=>'SELECT','table'=>'products','key'=>'id_product','value'=>'title'),
                                        'id_user'       => 'INPUT', 
                                        'status'        => array( 0 =>'Inactive', 1 =>'Active'),
                                    );

    protected $_fields_caption = array( 'id_user'       => array('model'=>'user','caption'=>'email'),
                                        'id_product'    => array('model'=>'product','caption'=>'title'),
                                         );

    protected $_search_fields = array('license','domain');

}
