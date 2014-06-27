<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Paymill helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class Paymill extends OC_Paymill{
	

    /**
     * generates HTML for apy buton
     * @param  Model_Product $product 
     * @return string                 
     */
    public static function button(Model_Product $product)
    {
        if ( Core::config('payment.paymill_private')!='' AND Core::config('payment.paymill_public')!='' AND Theme::get('premium')==1)
        {
            if (Auth::instance()->logged_in() AND $product->loaded())
                return View::factory('pages/paymill/button_loged',array('product'=>$product));
            elseif ($product->loaded())
                return View::factory('pages/paymill/button',array('product'=>$product));
        }

        return '';
    }

}