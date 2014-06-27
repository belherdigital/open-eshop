<?php defined('SYSPATH') or die('No direct script access.');

/**
 * bitpay helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class Bitpay extends OC_Bitpay{
	

    /**
     * generates HTML for apy buton
     * @param  Model_Product $product 
     * @return string                 
     */
    public static function button(Model_Product $product)
    {
        if ( Core::config('payment.bitpay_apikey')!='' AND Theme::get('premium')==1)
        {
            //we save a once session with how much you pay later used in the goal
            Session::instance()->set('goal_'.$product->id_product,$product->final_price());
            
            if (Auth::instance()->logged_in() AND $product->loaded())
                return View::factory('pages/bitpay/button_loged',array('product'=>$product));
            elseif ($product->loaded())
                return View::factory('pages/bitpay/button',array('product'=>$product));

        }

        return '';
    }

}