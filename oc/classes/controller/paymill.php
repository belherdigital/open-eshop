<?php defined('SYSPATH') or die('No direct script access.');

/**
* Paymill class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>, Slobodan Josifovic <slobodan@open-classifieds.com>
* @license GPL v3
*/

class Controller_Paymill extends Controller{
	

	public function after()
	{

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
                
            //Functions from https://github.com/paymill/paybutton-examples
            $privateApiKey  = Core::config('payment.paymill_private');

            if ( isset( $_POST[ 'paymillToken' ] ) ) 
            {
                $token = $_POST[ 'paymillToken' ];

                $client = Paymill::request(
                    'clients/',
                    array(),
                    $privateApiKey
                );

                $payment = Paymill::request(
                    'payments/',
                    array(
                         'token'  => $token,
                         'client' => $client[ 'id' ]
                    ),
                    $privateApiKey
                );

                $transaction = Paymill::request(
                    'transactions/',
                    array(
                         'amount'      => Paymill::money_format($order->amount),
                         'currency'    => $order->currency,
                         'client'      => $client[ 'id' ],
                         'payment'     => $payment[ 'id' ],
                         'description' => $order->product->title,
                    ),
                    $privateApiKey
                );

                if ( isset( $transaction[ 'status' ] ) && ( $transaction[ 'status' ] == 'closed' ) ) 
                {
                    //mark as paid
                    $order->confirm_payment('paymill',Core::post('paymillToken'), NULL, NULL, NULL,Paymill::calculate_fee($order->amount) );
                    
                    //redirect him to his ads
                    Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                    $this->redirect(Route::url('default', array('controller'=>'product','action'=>'goal','id'=>$order->id_order)));
                } 
                else 
                {
                    $msg = __('Transaction not successful!');
                    if ( ( !$transaction[ 'status' ] == 'closed' ) ) 
                        $msg.= ' - '. $transaction[ 'data' ][ 'error' ];

                    Kohana::$log->add(Log::ERROR, 'Paymill '.$msg);

                    Alert::set(Alert::ERROR, $msg);
                    $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));

                }
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
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