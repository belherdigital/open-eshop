<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Coupon
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_OC_Coupon extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'coupons';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_coupon';


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
                    'discount_amount'        => array(  array('price'),
                                                        array('range',array(':value',0,10000000000)),
                                                    ),
                    'discount_percentage'    => array(  array('price'),
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
        $form->fields['valid_date']['attributes']['placeholder']        = 'yyyy-mm-dd';
        $form->fields['valid_date']['attributes']['data-toggle']        = 'datepicker';
        $form->fields['valid_date']['attributes']['data-date']          = '';
        $form->fields['valid_date']['attributes']['data-date-format']   = 'yyyy-mm-dd';
    }

    /**
     * decreases de number available of coupon and deletes de cookie ;)
     * @param  model_coupon $coupon 
     * @return void         
     */
    public static function sale(Model_Coupon $coupon = NULL)
    {
        if ($coupon===NULL)
            $coupon = Model_Coupon::current();

        if ($coupon->loaded())
        {
            $coupon->number_coupons--;
            try {
                $coupon->save();
            } 
            catch (ORM_Validation_Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->errors(''));
            }
            catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
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
        if (Model_Coupon::$_current === NULL)
            Model_Coupon::$_current = Model_Coupon::get_coupon();

        return Model_Coupon::$_current;
    }


    /**
     * get the coupon from the query or from the sesion or the post in paypal
     * @return Model_Coupon or null if not found
     */
    public static function get_coupon($coupon_name = NULL)
    {
        if ($coupon_name===NULL)
            $coupon_name = core::post('custom',core::request('coupon',Session::instance()->get('coupon')));

        $coupon = new Model_Coupon();

        /**
         * Deletes a coupon in use
         */
        if(core::request('coupon_delete') != NULL)
        {
            Session::instance()->set('coupon','');
            Alert::set(Alert::INFO, __('Coupon deleted.'));
        }
        //selected coupon Paypal custom field, or coupon via get/post or session
        elseif( $coupon_name!==NULL AND !empty($coupon_name) )
        {
            $slug_coupon   = new Model_Coupon();
            $coupon = $slug_coupon->where('name', '=', $coupon_name )
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

    /**
     * tells if theres coupons active in the platform, to show the coupon form, or not ;)
     * @return bool 
     */
    public static function available()
    {
        $coupon   = new Model_Coupon();
        $coupon = $coupon
                    ->where('number_coupons','>',0)
                    ->where('valid_date','>',Date::unix2mysql())
                    ->where('status','=',1)
                    ->limit(1)->find();

        return $coupon->loaded();
    }


}