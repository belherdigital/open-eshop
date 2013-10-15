<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller SETTINGS contains all basic configurations displayed to Admin.
 */


class Controller_Panel_Settings extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Settings'))->set_url(Route::url('oc-panel',array('controller'  => 'settings'))));

    }

    public function action_index()
    {
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'settings','action'=>'general')));  
    }

    /**
     * Contains all data releated to new advertisment optional form inputs,
     * captcha, uploading text file  
     * @return [view] Renders view with form inputs
     */
	public function action_form()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Advertisement')));
        $this->template->title = __('Advertisement');
       
        // all form config values
        $advertisement = new Model_Config();
        $config = $advertisement->where('group_name', '=', 'advertisement')->find_all();


        // save only changed values
        if($this->request->post())
        {
            foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 

                if(isset($config_res))
                {
                    if($config_res !== $c->config_value)
                    {
                        $c->config_value = $config_res;
                        try {
                            $c->save();
                        } catch (Exception $e) {
                            echo $e;
                        }
                    }
                }
            }
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'form')));
            
        }

        $this->template->content = View::factory('oc-panel/pages/settings/advertisement', array('config'=>$config));
    }


    /**
     * Email configuration 
     * @return [view] Renders view with form inputs
     */
    public function action_email()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Email')));
        $this->template->title = __('Email');

        // all form config values
        $emailconf = new Model_Config();
        $config = $emailconf->where('group_name', '=', 'email')->find_all();

        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 

                if($config_res != $c->config_value)
                {
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Email Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'email')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/email', array('config'=>$config));
    }

    /**
     * All general configuration related with configuring site.
     * @return [view] Renders view with form inputs
     */
    public function action_general()
    {
        // validation active 
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('General')));
        $this->template->title = __('General');

        // all form config values
        $generalconfig = new Model_Config();
        $config = $generalconfig->where('group_name', '=', 'general')->find_all();
        $config_img = $generalconfig->where('group_name', '=', 'image')->find_all();

        foreach ($config as $c) 
        {
            $forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value);
        }
        
        foreach ($config_img as $c)
        {
            $forms_img[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value);
        }
        
        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {   
                
                $config_res = $this->request->post($c->config_key);
                if($config_res != $c->config_value)
                {
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
                  
            }
            foreach ($config_img as $ci) 
            {   
                
                $allowed_formats = '';
                $config_res = $this->request->post($ci->config_key);
                if($config_res != $ci->config_value)
                {
                    if($ci->config_key == 'allowed_formats')
                    {
                        
                      foreach ($config_res as $key => $value) 
                      {
                          $allowed_formats .= $value.",";
                      }
                      $config_res = $allowed_formats;
                    } 
                    
                    $ci->config_value = $config_res;
                    try {

                        $ci->save();

                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }

            
            Alert::set(Alert::SUCCESS, __('General Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'general')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/general', array('forms'=>$forms, 'forms_img'=>$forms_img));
    }

    /**
     * Payment deatails and paypal configuration can be configured here
     * @return [view] Renders view with form inputs
     */
    public function action_payment()
    {
        // validation active 
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Payments')));
        $this->template->title = __('Payments');

        // all form config values
        $paymentconf = new Model_Config();
        $config = $paymentconf->where('group_name', '=', 'payment')->find_all();
        
        $paypal_currency = Paypal::get_currency(); // currencies limited by paypal


        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 

                
                if($c->config_key == 'paypal_currency')
                {   
                    $config_res = $paypal_currency[core::post('paypal_currency')];
                }

                if($config_res != $c->config_value)
                {
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            
            Alert::set(Alert::SUCCESS, __('General Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'payment')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/payment', array('config'          => $config,
                                                                                          'paypal_currency' => $paypal_currency));
    }


}//end of controller