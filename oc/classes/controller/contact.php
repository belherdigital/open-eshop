<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends Controller {

    public function action_index()
    { 

        //template header
        $this->template->title              = __('Contact Us');
        $this->template->meta_description   = __('Contact').' '.core::config('general.site_name');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Contact Us')));

        if($this->request->post()) //message submition  
        {
            //captcha check
            if(captcha::check('contact'))
            {
                //check if user is loged in
                if (Auth::instance()->logged_in())
                {
                    $email_from = Auth::instance()->get_user()->email;
                    $name_from  = Auth::instance()->get_user()->name;
                }
                else
                {
                    $email_from = core::post('email');
                    $name_from  = core::post('name');
                }

                //akismet spam filter
                if(!core::akismet($name_from, $email_from,core::post('message')))
                {
                    $replace = array('[EMAIL.BODY]'     =>core::post('message'),
                                      '[EMAIL.SENDER]'  =>$name_from,
                                      '[EMAIL.FROM]'    =>$email_from);

                    if (Email::content(core::config('email.notify_email'),
                                        core::config('general.site_name'),
                                        $email_from,
                                        $name_from,'contact-admin',
                                        $replace))
                        Alert::set(Alert::SUCCESS, __('Your message has been sent'));
                    else
                        Alert::set(Alert::ERROR, __('Message not sent'));
                }
                else
                {
                    Alert::set(Alert::WARNING, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                }
            }
            else
                Alert::set(Alert::ERROR, __('Wrong captcha'));
                    
                
        }

        $this->template->content = View::factory('pages/contact');
        
    }

}
