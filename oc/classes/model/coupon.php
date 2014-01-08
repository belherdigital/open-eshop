<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Coupon
 *
 * @author      Chema <chema@garridodiaz.com>
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
    public static function sale($coupon = NULL)
    {
        if ($coupon!=NULL)
        {
            if ($coupon->loaded())
            {
                $coupon->number_coupons--;
                $coupon->save();
                Session::instance()->set('coupon','');
            }
        }
    }

    /**
     * get the coupon from the query or from the sesion or the post in paypal
     * @return Model_Coupon or null if not found
     */
    public static function get_coupon()
    {
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
            $slug_coupon   = new Model_Coupon();
            $coupon = $slug_coupon->where('name', '=', core::post('custom',core::request('coupon',Session::instance()->get('coupon'))) )
                    ->where('number_coupons','>',0)
                    ->where('valid_date','>',DB::expr('NOW()'))
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
                //return coupon
                return $coupon;
            }
            else
                Alert::set(Alert::INFO, __('Coupon not valid, expired or already used.'));
                
        }

        return NULL;
    }


}