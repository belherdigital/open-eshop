<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Social extends Controller {
	
	public function action_login()
	{
         //if user loged in redirect home
        if (Auth::instance()->logged_in())
            Auth::instance()->login_redirect();

		Social::include_vendor();
		$user = FALSE;	
		$config = Social::get();
		
		if ($this->request->query('hauth_start') OR $this->request->query('hauth_done'))
		{
			try 
			{
				Hybrid_Endpoint::process($this->request->query());
			} 
			catch (Exception $e) 
			{
				Alert::set(Alert::ERROR, $e->getMessage());
				$this->redirect(Route::url('default'));
			}
				
		}
		else
		{ 
			$provider_name = $this->request->param('id');
	 
			try
			{
				// initialize Hybrid_Auth with a given file
				$hybridauth = new Hybrid_Auth( $config );
	 
				// try to authenticate with the selected provider
                if ($provider_name == 'openid')
                    $params = array( 'openid_identifier' => 'https://openid.stackexchange.com/');
                else
                    $params = NULL;

				$adapter = $hybridauth->authenticate( $provider_name , $params);


				if ($hybridauth->isConnectedWith($provider_name)) 
				{
					//var_dump($adapter->getUserProfile());
                    $user_profile = $adapter->getUserProfile();
				}
			}
			catch( Exception $e )
			{
				Alert::set(Alert::ERROR, __('Error: please try again!')." ".$e->getMessage());
                $this->redirect(Route::url('default'));
			}

            //try to login the user with same provider and identifier
            $user = Auth::instance()->social_login($provider_name, $user_profile->identifier);

            //we couldnt login create account
            if ($user == FALSE)
            {
                $email = ($user_profile->emailVerified!=NULL)? $user_profile->emailVerified: $user_profile->email;
                $name  = ($user_profile->firstName!=NULL)? $user_profile->firstName.' '.$user_profile->lastName: $user_profile->displayName;
                //if not email provided 
                if (!Valid::email($email,TRUE))
                {
                    Alert::set(Alert::INFO, __('We need your email address to complete'));
                    //redirect him to select the email to register
                    $this->redirect(Route::url('default',array('controller'=>'social',
                                                                        'action'=>'register',
                                                                        'id'    =>$provider_name)).'?uid='.$user_profile->identifier.'&name='.$name);
                }
                else
                {
                    //register the user in DB
                    Model_User::create_social($email,$name,$provider_name,$user_profile->identifier);
                    //log him in
                    Auth::instance()->social_login($provider_name, $user_profile->identifier);
                }
            }
            else                    
                Alert::set(Alert::SUCCESS, __('Welcome!'));

            $this->redirect(Session::instance()->get_once('auth_redirect',Route::url('default')));

		} 
	}

    /**
     * simple registration without password
     * @return [type] [description]
     */
    public function action_register()
    {
        $provider_name = $this->request->param('id');

        $this->template->content = View::factory('pages/auth/register-social', array('provider'=>$provider_name,
                                                                                'uid'=>core::get('uid'),
                                                                                'name'=>core::get('name')));

        if (core::post('email') AND CSRF::valid('register_social'))
        {
            $email = core::post('email');
                
            if (Valid::email($email,TRUE))
            {
                //register the user in DB
                Model_User::create_social($email,core::post('name'),$provider_name,core::get('uid'));
                //log him in
                Auth::instance()->social_login($provider_name,core::get('uid'));

                Alert::set(Alert::SUCCESS, __('Welcome!'));

                //change the redirect
                $this->redirect(Route::url('default'));
            }
            else
            {
                Form::set_errors(array(__('Invalid Email')));
            }
                
        }
    
        //template header
        $this->template->title            = __('Register new user');
            
    }
}	