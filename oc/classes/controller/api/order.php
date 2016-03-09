<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Order extends Api_Auth {

    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $output = array();

                $orders = new Model_Order();

                //filter results by param, verify field exists and has a value and sort the results
                $orders->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $orders->count_all();

                //by default sort by created date
                if(empty($this->_sort))
                    $this->_sort['created'] = 'desc';

                //after counting sort values
                $orders->api_sort($this->_sort);

                //pagination with headers
                $pagination = $orders->api_pagination($count,$this->_params['items_per_page']);

                $orders = $orders->cached()->find_all();

                //as array
                foreach ($orders as $order)
                    $output[] = self::get_order_array($order);

                $this->rest_output(array('orders' => $output),200,$count,($pagination!==FALSE)?$pagination:NULL);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    /**
     * Handle GET requests.
     */
    public function action_get()
    {
        try
        {
            $order = new Model_order();

            if (is_numeric($id_order = $this->request->param('id')))
            {
                $order = new Model_order($id_order);
            }
            elseif ( Valid::email(core::request('email')) AND is_numeric(core::request('id_product')) )
            {
                $order->join('users')
                        ->using('id_user')
                        ->where('email','=',core::request('email'))
                        ->where('id_product','=',core::request('id_product'))
                        ->find();
            }
            
            if ($order->loaded())
                $this->rest_output(array('order' => self::get_order_array($order)));
            else
                $this->_error(__('Order not found'),404);
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    public function action_create()
    {
        try
        {
            if (!Valid::email(core::request('email')))
                $this->_error(__('Invalid email'),501);
            elseif (!is_numeric(core::request('id_product')))
                $this->_error(__('Invalid product'),501);
            else
            {
                $product = new Model_Product(core::request('id_product'));

                if($product->loaded())
                {
                    $user = Model_User::create_email(core::request('email'),core::request('name'));

                    $order = Model_Order::new_order($user, $product);
                    $order->confirm_payment(core::request('paymethod','API'), core::request('txn_id'),
                                            core::request('pay_date'),core::request('amount'),
                                            core::request('currency'),core::request('fee'));

                    //adding the notes
                    $order->notes = core::request('notes');
                    $order->save();

                    //in case the device id or domain is provided
                    if (core::request('device_id')!==NULL OR core::request('domain')!==NULL)
                    {
                        $license = $order->licenses->find(1);
                        if ($license->loaded())
                        {
                            if (core::request('device_id')!==NULL)
                                Model_License::verify_device($license->license,core::request('device_id')); 

                            if (core::request('domain')!==NULL)
                                Model_License::verify($license->license,core::request('domain')); 

                        }
                    }
                    

                    $this->rest_output(array('order' => self::get_order_array($order)));
                } 
                else
                    $this->_error(__('Something went wrong'),501);
            }
            
            
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }


    public static function get_order_array($order)
    {
        $o = $order->as_array();
        $o['user']['id'] = $order->user->id_user;
        $o['user']['email'] = $order->user->email;
        $o['product'] = $order->product->as_array();
        $o['coupon'] = ($order->coupon->loaded())?$order->coupon->as_array():NULL;
        foreach ($order->licenses->find_all() as $license) 
            $o['licenses'][] = $license->as_array();

        return $o;
    }



} // END