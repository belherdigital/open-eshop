
<?php defined('SYSPATH') or die('No direct script access.');

/**
* paypal class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>, Slobodan Josifovic <slobodan@open-classifieds.com>
* @license GPL v3
*/

class Controller_Paypal extends Controller{
	

	public function after()
	{

	}

    public function action_ipn()
    {
        //todo delete
        //paypal::validate_ipn();

        $this->auto_render = FALSE;

        //START PAYPAL IPN
        //manual checks
        $id_order         = Core::post('item_number');
        $paypal_amount    = Core::post('mc_gross');
        $payer_id         = Core::post('payer_id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();
        
        if($order->loaded())
        {

            //same amount and same currency
            if ( Core::post('payment_status')   == 'Completed' 
                AND  Core::post('mc_gross')     == number_format($order->amount, 2, '.', '')
                AND  Core::post('mc_currency')  == $order->currency 
                AND ( Core::post('receiver_email')  == core::config('payment.paypal_account') 
                       || Core::post('business')    == core::config('payment.paypal_account') )
                )
            {
                //same price , currency and email no cheating ;)
                if (paypal::validate_ipn()) 
                {
                    $order->confirm_payment('paypal',Core::post('txn_id'), NULL, NULL, NULL,Core::post('mc_fee') ); 
                }
                else
                {
                    Kohana::$log->add(Log::ERROR, 'A payment has been made but is flagged as INVALID');
                    $this->response->body('KO');
                }   
            } 
            else //trying to cheat....
            {
                Kohana::$log->add(Log::ERROR, 'Attempt illegal actions with transaction');
                $this->response->body('KO');
            }
        }// END order loaded
        else
        {
            Kohana::$log->add(Log::ERROR, 'Order not loaded');
            $this->response->body('KO');
        }

        $this->response->body('OK');
    } 
	



    public function action_pay()
    { 
        $this->auto_render = FALSE;

        $order_id = $this->request->param('id');


        $order = new Model_Order();

        $order->where('id_order','=',$order_id)
            ->where('status','=',Model_Order::STATUS_CREATED)
            ->limit(1)->find();

        if ($order->loaded())
        {

            $paypal_url = (Core::config('payment.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;

            $paypal_data = array('order_id'             => $order_id,
                                 'amount'               => number_format($order->amount, 2, '.', ''),
                                 'site_name'            => core::config('general.site_name'),
                                 'return_url'           => Route::url('default', array('controller'=>'product','action'=>'goal','id'=>$order->id_order)),
                                 'paypal_url'           => $paypal_url,
                                 'paypal_account'       => core::config('payment.paypal_account'),
                                 'paypal_currency'      => $order->currency,
                                 'item_name'            => $order->product->title);
            
            $this->template = View::factory('paypal', $paypal_data);
            $this->response->body($this->template->render());
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default'));
        }
    }

}