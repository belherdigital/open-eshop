
<?php defined('SYSPATH') or die('No direct script access.');

/**
* paypal class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@garridodiaz.com>, Slobodan Josifovic <slobodan.josifovic@gmail.com>
* @license GPL v3
*/

class Controller_Paypal extends Controller{
	

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

			if (	Core::post('mc_gross')          == number_format($product->final_price(), 2, '.', '')
				&&  Core::post('mc_currency')       == $product->currency
				&& (Core::post('receiver_email')    == core::config('payment.paypal_account') 
					|| Core::post('business')       == core::config('payment.paypal_account')))
			{//same price , currency and email no cheating ;)
                if (paypal::validate_ipn()) 
				{

                    if (!Auth::instance()->logged_in())
                    {
                        //create user if doesnt exists
                         //send email to user with password
                        $user = Model_User::create_email(Core::post('payer_email'),Core::post('first_name').' '.Core::post('last_name'));

                    }
                    else//he was loged so we use his user
                        $user = Auth::instance()->get_user();
					
                    Model_Order::sale(NULL,$user,$product,Core::post('txn_id'),'paypal');
                        
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

        $product_seo = $this->request->param('seotitle');

        $product = new Model_product();

        $product->where('seotitle','=',$product_seo)
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        ///testing
        // $user = Model_User::create_email('neo22s@gmail.com','chema');
        // Model_Order::sale(NULL,$user,$product,time(),'paypal');
        // d('sd');

        if ($product->loaded())
        {
            //we save a once session with how much you pay later used in the goal
            Session::instance()->set('goal_'.$product->id_product,$product->final_price());

			$paypal_url = (Core::config('payment.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;

		 	$paypal_data = array('product_id'           => $product->id_product,
	                             'amount'            	=> number_format($product->final_price(), 2, '.', ''),
	                             'site_name'        	=> core::config('general.site_name'),
	                             'return_url'           => Route::url('product-goal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)),
	                             'paypal_url'        	=> $paypal_url,
	                             'paypal_account'    	=> core::config('payment.paypal_account'),
	                             'paypal_currency'    	=> $product->currency,
	                             'item_name'			=> $product->title,
                                 'coupon'               => (Controller::$coupon!==NULL)?Controller::$coupon->name:'',
                                 );
			
			$this->template = View::factory('paypal', $paypal_data);
            $this->response->body($this->template->render());
			
		}
		else
		{
			Alert::set(Alert::INFO, __('Product could not be loaded'));
            $this->request->redirect(Route::url('default'));
		}
	}

}