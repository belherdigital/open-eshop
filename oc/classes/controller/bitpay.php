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

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {

            //options send to create the invoice
            $options = array(   'buyerName'     => $order->user->name,
                                'buyerEmail'    => $order->user->email,
                                'currency'      => $order->currency,
                                'redirectURL'   => Route::url('default', array('controller'=>'product','action'=>'goal','id'=>$order->id_order)),
                                'notificationURL' => Route::url('default',array('controller'=>'bitpay','action'=>'ipn','id'=>$id_order))
                            );

            $invoice = Bitpay::bpCreateInvoice($order->id_order, $order->amount, '', $options);
            
            if (!isset($invoice['error']) AND valid::url($invoice['url']))
                $this->redirect($invoice['url']);
            else
            {
                Alert::set(Alert::INFO, __('Could not create bitpay invoice'));
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
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

        $id_order = $this->request->param('id');
        
        //ipn result validated
        $ipn_result = Bitpay::bpVerifyNotification();

        if (isset($ipn_result['error']))
            Kohana::$log->add(Log::ERROR, $response);
        else
        {
            
            //retrieve info for the item in DB
            $order = new Model_Order();
            $order = $order->where('id_order', '=', $id_order)
                           ->where('status', '=', Model_Order::STATUS_CREATED)
                           ->limit(1)->find();

            if ($order->loaded())
            {
                switch($ipn_result['status'])
                {
                    case 'paid':
                        break;
                    case 'confirmed':
                        Kohana::$log->add(Log::DEBUG,'BitPay bitcoin payment confirmed. Awaiting network confirmation and completed status.');
                    case 'complete':
                        //mark as paid
                        $order->confirm_payment('bitpay', (isset($ipn_result['id']))?$ipn_result['id']:'' );
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