<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends Controller {

	public function action_index()
	{ 

		//template header
		$this->template->title           	= __('Contact Us');
		$this->template->meta_description	= __('Contact Us');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Contact Us')));

		if($this->request->post()) //message submition  
		{
            //captcha check
            if(captcha::check('contact'))
            {
                //akismet spam filter
                if(!core::akismet(core::post('name'), 
                                  core::post('email'),
                                  core::post('message')))
                {
                    $replace = array('[EMAIL.BODY]'     =>core::post('message'),
                                      '[EMAIL.SENDER]'  =>core::post('name'),
                                      '[EMAIL.FROM]'    =>core::post('email'));

                    if (Email::content(core::config('email.notify_email'),
                                        core::config('general.site_name'),
                                        core::post('email'),
                                        core::post('name'),'contact-admin',
                                        $replace))
                        Alert::set(Alert::SUCCESS, __('Your message has been sent'));
                    else
                        Alert::set(Alert::ERROR, __('Message not sent'));
                }
                else
                {
                    Alert::set(Alert::SUCCESS, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                }
            }
            else
                Alert::set(Alert::ERROR, __('Check the form for errors'));
					
				
		}

        $this->template->content = View::factory('pages/contact');
		
	}

}