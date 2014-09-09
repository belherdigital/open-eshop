<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Coupon
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Coupon extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'coupons';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_coupon';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'product' => array(
                'model'       => 'product',
                'foreign_key' => 'id_product',
            ),
    );

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
        return array(
                    'valid_date'             => array(  array('not_empty'),array('date') ),
                    'number_coupons'         => array(  array('not_empty'),
                                                        array('numeric'),
                                                        array('range',array(':value',0,10000000000)),
                                                    ),
                    'discount_amount'        => array(  array('numeric'),
                                                        array('range',array(':value',0,10000000000)),
                                                    ),
                    'discount_percentage'    => array(  array('numeric'),
                                                        array('range',array(':value',0,100)),
                                                    ),
                    'notes'                   => array(
                                                        array('max_length', array(':value', 245)),
                                                    ),
                    'name'                   => array(
                                                        array('not_empty'),
                                                        array('max_length', array(':value', 145)),
                                                        array('min_length', array(':value', 3)),
                                                        array(array($this, 'unique'), array('name', ':value')),
                                                    ),
                );
    }

    /**
     * global Model Coupon instance get from controller so we can access from anywhere like Model_Coupon::current()
     * @var Model_Coupon
     */
    protected static $_current = NULL;
    

    public function exclude_fields()
    {
        return array('created');
    }

    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   

        $form->fields['id_product']['display_as']   = 'select';
        $form->fields['id_product']['caption']      = 'title';  
    }

    /**
     * decreases de number available of coupon and deletes de cookie ;)
     * @param  model_coupon $coupon 
     * @return void         
     */
    public static function sale(Model_Coupon $coupon = NULL)
    {
        if ($coupon===NULL)
            $coupon = self::current();

        if ($coupon->loaded())
        {
            $coupon->number_coupons--;
            $coupon->save();
            Session::instance()->set('coupon','');
        }
    }



    /**
     * returns the current category
     * @return Model_Category
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
            self::$_current = self::get_coupon();

        return self::$_current;
    }


    /**
     * get the coupon from the query or from the sesion or the post in paypal
     * @return Model_Coupon or null if not found
     */
    public static function get_coupon()
    {
        $coupon = new self();

        /**
         * Deletes a coupon in use
         */
        if(core::request('coupon_delete') != NULL)
        {
            Session::instance()->set('coupon','');
            Alert::set(Alert::INFO, __('Coupon deleted.'));
        }
        //selected coupon Paypal custom field, or coupon via get/post or session
        elseif(core::post('custom') != NULL OR core::request('coupon') != NULL OR Session::instance()->get('coupon')!='' )
        {
            $slug_coupon   = new self();
            $coupon = $slug_coupon->where('name', '=', core::post('custom',core::request('coupon',Session::instance()->get('coupon'))) )
                    ->where('number_coupons','>',0)
                    ->where('valid_date','>',Date::unix2mysql())
                    ->where('status','=',1)
                    ->limit(1)->find();
            if ($coupon->loaded())
            {
                //only add it to session if its different than before
                if (Session::instance()->get('coupon')!=$coupon->name)
                {
                    Alert::set(Alert::SUCCESS, __('Coupon added!'));
                    Session::instance()->set('coupon',$coupon->name);
                }
                
            }
            else
            {
                Alert::set(Alert::INFO, __('Coupon not valid, expired or already used.'));
                Session::instance()->set('coupon','');
            }
                
        }

        return $coupon;
    }


}