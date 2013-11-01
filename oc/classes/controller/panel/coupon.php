<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Coupon extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_coupon','name','valid_date','number_coupons','discount_amount','discount_percentage','created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'coupon';

}
