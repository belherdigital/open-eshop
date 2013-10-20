<?php defined('SYSPATH') or die('No direct script access.');

/**
* Paymill class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@garridodiaz.com>, Slobodan Josifovic <slobodan.josifovic@gmail.com>
* @license GPL v3
*/

class Controller_Paymill extends Controller{
	

	public function after()
	{

	}
	
	public function action_ipn()
	{
		$this->auto_render = FALSE;

		//START PAYPAL IPN
		//manual checks
		$id_product       = Core::post('item_number');
		$paypal_amount    = Core::post('mc_gross');
		$payer_id         = Core::post('payer_id');

		//retrieve info for the item in DB
		$product = new Model_product();
		$product = $product->where('id_product', '=', $id_product)
					   ->where('status', '=', Model_Product::STATUS_ACTIVE)
					   ->limit(1)->find();
		
		if($product->loaded())
		{
			if (	Core::post('mc_gross')          == number_format($product->price, 2, '.', '')
				&&  Core::post('mc_currency')       == $product->currency
				&& (Core::post('receiver_email')    == core::config('payment.paypal_account') 
					|| Core::post('business')       == core::config('payment.paypal_account')))
			{//same price , currency and email no cheating ;)
                if (paypal::validate_ipn()) 
				{
					//create user if doesnt exists
                         //send email to user with password
                    $user = Model_User::create_email(Core::post('payer_email'),Core::post('first_name').' '.Core::post('last_name'));

                    Model_Order::create_order(NULL,$user,$product,Core::post('txn_id'),'paypal');
                        
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
            Kohana::$log->add(Log::ERROR, 'Product not loaded');
            $this->response->body('KO');
		}

		$this->response->body('OK');
	} 

	/**
	 * [action_form] generates the form to pay at paypal
	 */
	public function action_pay()
	{ 
		$this->auto_render = FALSE;

        $seotitle = $this->request->param('id');

        $product = new Model_product();
        $product->where('seotitle','=',$seotitle)
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            //Functions from https://github.com/paymill/paybutton-examples
            $privateApiKey  = Core::config('payment.paymill_private');

            if ( isset( $_POST[ 'paymillToken' ] ) ) 
            {
                $token = $_POST[ 'paymillToken' ];

                $client = request(
                    'clients/',
                    array(),
                    $privateApiKey
                );

                $payment = request(
                    'payments/',
                    array(
                         'token'  => $token,
                         'client' => $client[ 'id' ]
                    ),
                    $privateApiKey
                );

                $transaction = request(
                    'transactions/',
                    array(
                         'amount'      => $Paymill::money_format($product->price),
                         'currency'    => $product->currency,
                         'client'      => $client[ 'id' ],
                         'payment'     => $payment[ 'id' ],
                         'description' => $product->title,
                    ),
                    $privateApiKey
                );

                if ( isset( $transaction[ 'status' ] ) && ( $transaction[ 'status' ] == 'closed' ) ) 
                {
                    echo '<strong>Transaction successful! ask for email address.</strong>';
                    // $this->template = View::factory('paypal', $paypal_data);
                    // $this->response->body($this->template->render());
                    die();
                } 
                else 
                {
                    $msg = __('Transaction not successful!');
                    if ( ( !$transaction[ 'status' ] == 'closed' ) ) 
                        $msg.= ' - '. $transaction[ 'data' ][ 'error' ];

                    Kohana::$log->add(Log::ERROR, 'PAymill '.$msg);

                    Alert::set(Alert::ERROR, $msg);
                    $this->request->redirect(Route::url('default'));

                }
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->request->redirect(Route::url('product', array('seotitle'=>$product->seotitle)));
            }
			
		}
		else
		{
			Alert::set(Alert::INFO, __('Product could not be loaded'));
            $this->request->redirect(Route::url('product', array('seotitle'=>$product->seotitle)));
		}
	}

}