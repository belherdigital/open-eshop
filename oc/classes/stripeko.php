<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Stripe helper class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class StripeKO {
	

    /**
     * generates HTML for apy buton
     * @param  Model_Product $product 
     * @return string                 
     */
    public static function button(Model_Product $product)
    {
        if ( Core::config('payment.stripe_private')!='' AND Core::config('payment.stripe_public')!='' )
        {
            //we save a once session with how much you pay later used in the goal
            Session::instance()->set('goal_'.$product->id_product,$product->final_price());
            
            return View::factory('pages/stripe/button',array('product'=>$product));
        }

        return '';
    }

    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount 
     * @return string         
     */
    public static function money_format($amount)
    {
        return str_replace(array(',','.'), '', $amount);
    }



}