<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Coupon extends Api_Controller {

    /**
     * Handle GET requests.
     */
    public function action_get()
    {
        try
        {
            if (($coupon_name = $this->request->param('id'))!=NULL )
            {
                $coupon   = new Model_Coupon();
                $coupon ->where('name', '=', $coupon_name )
                        ->where('number_coupons','>',0)
                        ->where('valid_date','>',Date::unix2mysql())
                        ->where('status','=',1);

                //filter by product
                if (is_numeric(core::request('id_product')))
                    $coupon->where('id_product','=',core::request('id_product'));

                $coupon = $coupon->limit(1)->find();

                $this->rest_output(array('coupon' => ($coupon->loaded())?$coupon->as_array():FALSE));
            }    
                                   
            else
                $this->_error('You need to specify a coupon');
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

} // END