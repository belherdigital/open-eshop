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
     * @param  Model_Product $product 
     * @return string                 
     */
    public static function form(Model_Product $product)
    {
        if ( Core::config('payment.authorize_login')!='' AND Core::config('payment.authorize_key')!='' )
        {
            if (Auth::instance()->logged_in() AND $product->loaded())
                return View::factory('pages/authorize/form',array('product'=>$product));
            elseif ($product->loaded())
                return View::factory('pages/authorize/button');
        }
        return '';
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

        if ($product->loaded() AND Auth::instance()->logged_in())
        {
            // include class vendor
            require Kohana::find_file('vendor/authorize/', 'autoload');

            define('AUTHORIZENET_API_LOGIN_ID', Core::config('payment.authorize_login'));
            define('AUTHORIZENET_TRANSACTION_KEY', Core::config('payment.authorize_key'));
            define('AUTHORIZENET_SANDBOX', Core::config('payment.authorize_sandbox'));
            $sale           = new AuthorizeNetAIM;
            $sale->amount   = $product->amount;
            $sale->card_num = Core::post('card-number');
            $sale->exp_date = Core::post('expiry-month').'/'.Core::post('expiry-year');
            $response = $sale->authorizeAndCapture();
            if ($response->approved) 
            {
                //create order
                $order = Model_Order::sale(NULL,Auth::instance()->get_user(),$product,$response->transaction_id,'authorize');
                
                //redirect him to the thanks page
                $this->redirect(Route::url('product-goal', array('seotitle'=>$product->seotitle,
                                                                          'category'=>$product->category->seoname,
                                                                          'order'   =>$order->id_order)));
                
            }
            else
            {
                Alert::set(Alert::INFO, $response->error_message);
                $this->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
            }  
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Product could not be loaded'));
            $this->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
        }
    }


}