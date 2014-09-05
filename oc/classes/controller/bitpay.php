<?php defined('SYSPATH') or die('No direct script access.');

/**
* bitpay class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_Bitpay extends Controller{
	

    /**
     * generates the request to pay at bitpay
     */
    public function action_pay()
    { 
        $this->auto_render = FALSE;

        $product_seo = $this->request->param('id');

        $product = new Model_product();

        $product->where('seotitle','=',$product_seo)
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();


        if ($product->loaded())
        {
            //user needs to be loged
            if (Auth::instance()->logged_in())
                $user = Auth::instance()->get_user();
            else
            {
                Alert::set(Alert::INFO, __('Please login before purchasing'));
                $this->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
            }

            //we save a once session with how much you pay later used in the goal
            Session::instance()->set('goal_'.$product->id_product,$product->final_price());


            //options send to create the invoice
            $options = array(   'buyerName'     => $user->name,
                                'buyerEmail'    => $user->email,
                                'currency'      => $product->currency,
                                'redirectURL'   => Route::url('product-goal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))
                            );

            $invoice = Bitpay::bpCreateInvoice($product->id_product, $product->final_price(), '', $options);
            
            if (!isset($invoice['error']) AND valid::url($invoice['url']))
                $this->redirect($invoice['url']);
            else
            {
                Alert::set(Alert::INFO, __('Could not create bitpay invoice'));
                $this->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
            }
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Product could not be loaded'));
            $this->redirect(Route::url('default'));
        }
    }

    public function action_ipn()
    {
        $this->auto_render = FALSE;

        //ipn result validated
        $ipn_result = Bitpay::bpVerifyNotification();

        if (isset($ipn_result['error']))
            Kohana::$log->add(Log::ERROR, $response);
        else
        {
            if (!Auth::instance()->logged_in())
                $user = Model_User::create_email($ipn_result['buyerFields']['buyerEmail'],$ipn_result['buyerFields']['buyerName']);
            else//he was loged so we use his user
                $user = Auth::instance()->get_user();

            $product = new Model_product();
            $product->where('id_product','=',$ipn_result['orderId'])
                ->where('status','=',Model_Product::STATUS_ACTIVE)
                ->limit(1)->find();


            if ($product->loaded())
            {
                switch($ipn_result['status'])
                {
                    case 'paid':
                        break;
                    case 'confirmed':
                        Kohana::$log->add(Log::DEBUG,'BitPay bitcoin payment confirmed. Awaiting network confirmation and completed status.');
                    case 'complete':

                        $order = Model_Order::sale(NULL,$user,$product,Core::post('txn_id'),'bitpay');
                        $this->response->body('OK');

                        break;
                    case 'invalid':
                        Kohana::$log->add(Log::ERROR,  'Bitcoin payment is invalid for this order! The payment was not confirmed by the network within 1 hour.' );
                        break;
                }
            }
        }
        $this->response->body('KO');


        
    } 




}