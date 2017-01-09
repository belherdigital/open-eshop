<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Stripe helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class OC_StripeKO {
    
    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount 
     * @return string         
     */
    public static function money_format($amount)
    {
        return round($amount,2)*100;
    }

    /**
     * how much the site owner earn?
     * @param  integer $amount 
     * @param  integer $fee 
     * @return integer
     */
    public static function application_fee($amount, $fee = NULL)
    {   
        //percentage we take, in case not passed take default
        if ($fee === NULL)
            $fee  = Core::config('payment.stripe_appfee');

        //initial exchange fee + stripe fee
        return ($fee * $amount / 100);
    }


    /**
     *   NOTE This will  never be exactly since stripe has variable pricing
     */
    public static function calculate_fee($amount)
    {   
        //variables
        $fee            = 2.9;
        $fee_trans      = 0.3;//USD

        //initial exchange fee + stripe fee
        return ($fee * $amount / 100) + $fee_trans;
    }
    
    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.stripe_private')!='' AND Core::config('payment.stripe_public')!='' AND Theme::get('premium')==1)
        {
            return View::factory('pages/stripe/button',array('order'=>$order));
        }

        return '';
    }


    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button_connect(Model_Order $order)
    {
        if ( !empty($order->ad->user->stripe_user_id) AND
            Core::config('payment.stripe_connect')==TRUE AND  
            Core::config('payment.stripe_private')!='' AND 
            Core::config('payment.stripe_public')!='' AND 
            Theme::get('premium')==1 AND
            $order->id_product == Model_Order::PRODUCT_AD_SELL )
        {
            if ($order->ad->price != NULL AND $order->ad->price > 0 AND 
                (core::config('payment.stock')==0 OR ($order->ad->stock > 0 AND core::config('payment.stock')==1)))
                return View::factory('pages/stripe/button_connect',array('order'=>$order));
        }

        return '';
    }
}