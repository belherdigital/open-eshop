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
                         'amount'      => Paymill::money_format($product->final_price()),
                         'currency'    => $product->currency,
                         'client'      => $client[ 'id' ],
                         'payment'     => $payment[ 'id' ],
                         'description' => $product->title,
                    ),
                    $privateApiKey
                );

                if ( isset( $transaction[ 'status' ] ) && ( $transaction[ 'status' ] == 'closed' ) ) 
                {
                    //echo '<strong>Transaction successful! ask for email address.</strong>';
                    //if (Auth::instance()->logged_in())
                    
                    //create order
                    $order = Model_Order::sale(NULL,Auth::instance()->get_user(),$product,Core::post('paymillToken'),'paymill');
                    //redirect him to the download
                    Alert::set(Alert::SUCCESS, __('Thanks for your purchase!'));
                    $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));

                } 
                else 
                {
                    $msg = __('Transaction not successful!');
                    if ( ( !$transaction[ 'status' ] == 'closed' ) ) 
                        $msg.= ' - '. $transaction[ 'data' ][ 'error' ];

                    Kohana::$log->add(Log::ERROR, 'Paymill '.$msg);

                    Alert::set(Alert::ERROR, $msg);
                    $this->request->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));

                }
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->request->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
            }
			
		}
		else
		{
			Alert::set(Alert::INFO, __('Product could not be loaded'));
            $this->request->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
		}
	}

}