<?php defined('SYSPATH') or die('No direct script access.');

/**
* Authorize class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_Authorize extends Controller{
    
    /**
     * generates HTML form
     * @param  Model_Product $order 
     * @return string                 
     */
    public static function form(Model_Order $order)
    {
        if ( Core::config('payment.authorize_login')!='' AND 
            Core::config('payment.authorize_key')!='' AND
            Auth::instance()->logged_in() AND $order->loaded() AND Theme::get('premium')==1)
        {
            return View::factory('pages/authorize/form',array('order'=>$order));
           
        }
        return '';
    }

    /**
     *   Seems to have an exact price http://www.authorize.net/solutions/merchantsolutions/pricing/
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
     * [action_form] generates the form to pay at paypal
     */
    public function action_pay()
    { 
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {
            //its a fraud...lets let him know
            if ( $order->is_fraud() === TRUE )
            {
                Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
            }
                
            // include class vendor
            require Kohana::find_file('vendor/authorize/', 'autoload');

            define('AUTHORIZENET_API_LOGIN_ID', Core::config('payment.authorize_login'));
            define('AUTHORIZENET_TRANSACTION_KEY', Core::config('payment.authorize_key'));
            define('AUTHORIZENET_SANDBOX', Core::config('payment.authorize_sandbox'));
            $sale           = new AuthorizeNetAIM;
            $sale->amount   = $order->amount;
            $sale->card_num = Core::post('card-number');
            $sale->exp_date = Core::post('expiry-month').'/'.Core::post('expiry-year');
            $response = $sale->authorizeAndCapture();
            if ($response->approved) 
            {
                $order->confirm_payment('authorize',$response->transaction_id, NULL, NULL, NULL,Controller_Authorize::calculate_fee($order->amount) );
                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!').' '.$response->transaction_id);
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'goal','id'=>$order->id_order)));
            }
            else
            {
                Alert::set(Alert::INFO, $response->error_message);
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
            }  
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
        }
    }



}