<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Auth extends Controller {
    
    /**
     * 
     * Check if we need to login the user or display the form, same form for normal user and admin
     */
	public function action_login()
	{		
	    //if user loged in redirect home
	    if (Auth::instance()->logged_in())
	    {
	    	Auth::instance()->login_redirect();
	    }
	    //posting data so try to login
	    elseif ($this->request->post() AND CSRF::valid('login'))
	    {
            $blocked_login = FALSE;

            // Load the user
            $user = new Model_User;
            $user   ->where('email', '=', core::post('email'))
                    ->where('status', 'in', array(Model_User::STATUS_ACTIVE,Model_User::STATUS_SPAM))
                    ->limit(1)
                    ->find();

            // Check if we must block this login attempt.
            if ($user->loaded() AND $user->failed_attempts > 2) {
                // failed 2 or 3 attempts, wait 1 minute until next attempt
                if ($user->failed_attempts < 5 AND $user->last_failed > Date::unix2mysql(strtotime('-1 minute')))
                {
                    $blocked_login = TRUE;
                    Alert::set(Alert::ERROR, __('Login has been temporarily disabled due to too many unsuccessful login attempts. Please try again in a minute.'));
                }
                // failed more than 4 attempts, wait 24 hours until next attempt
                elseif ($user->failed_attempts > 4 AND $user->last_failed > Date::unix2mysql(strtotime('-24 hours')))
                {
                    $blocked_login = TRUE;
                    Alert::set(Alert::ERROR, __('Login has been temporarily disabled due to too many unsuccessful login attempts. Please try again in 24 hours.'));                    
                }
            }

            //not blocked so try to login
            if (! $blocked_login)
            {
                Auth::instance()->login(core::post('email'), 
                                        core::post('password'),
                                        (bool) core::post('remember'));

                //redirect index
                if (Auth::instance()->logged_in())
                {
                    if ($user->loaded())
                    {
                        $user->failed_attempts = 0;

                        try 
                        {
                            // Save the user
                            $user->update();
                        }
                        catch (ORM_Validation_Exception $e)
                        {
                            Form::set_errors($e->errors(''));
                        }
                        catch(Exception $e)
                        {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }                    

                    //is an admin so redirect to the admin home
                    Auth::instance()->login_redirect();
                }
                else 
                {
                    Form::set_errors(array( __('Wrong email or password').'. '
                                            .'<a class="alert-link" href="'.Route::url('oc-panel',array(   'directory'=>'user',
                                                                                        'controller'=>'auth',
                                                                                        'action'=>'forgot'))
                                            .'">'.__('Have you forgotten your password?').'</a>'));
                    if ($user->loaded())
                    {
                        // this is fifth failed attempt, invalidate token?
                        if ($user->failed_attempts == 4) {
                            $user->token            = NULL;                            
                            $user->user_agent       = NULL;                            
                            $user->token_created    = NULL;                            
                            $user->token_expires    = NULL;                            
                        }

                        $user->failed_attempts = new Database_Expression('failed_attempts + 1');
                        $user->last_failed = Date::unix2mysql(time());

                        try 
                        {
                            // Save the user
                            $user->update();
                        }
                        catch (ORM_Validation_Exception $e)
                        {
                            Form::set_errors($e->errors(''));
                        }
                        catch(Exception $e)
                        {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }
                }
            }
	    }
	    	    
	    //Login page
	    $this->template->title = __('Login');	    
		$this->template->meta_description = __('Login to').' '.Core::config('general.site_name');    
	    $this->template->content = View::factory('pages/auth/login');
	}
	
    /**
     * 
     * Logout user session
     */
    public function action_logout()
    {
        Auth::instance()->logout(TRUE);    

        if(Valid::URL($this->request->referrer()) AND strpos($this->request->referrer(), 'oc-panel')===FALSE)
            $redir  = $this->request->referrer();
        else
            $redir = Route::url('oc-panel',array('controller'=>'auth','action'=>'login'));

        $this->redirect($redir);
    
    }
	
	/**
	 * Sends an email with a link to change your password
	 * 
	 */
	public function action_forgot()
	{
        //template header
        $this->template->title            = __('Remember password');
        $this->template->meta_description = __('Here you can reset your password if you forgot it');  
		$this->template->content = View::factory('pages/auth/forgot');
		
		//if user loged in redirect home
		if (Auth::instance()->logged_in())
		{
			$this->redirect(Route::get('oc-panel')->uri());
		}
		//posting data so try to remember password
		elseif (core::post('email') AND CSRF::valid('forgot'))
		{
			$email = core::post('email');
			
			if (Valid::email($email,TRUE))
			{
				//check we have this email in the DB
				$user = new Model_User();
				$user = $user->where('email', '=', $email)
							->limit(1)
							->find();
				
				if ($user->loaded())
				{
					
                    //we get the QL, and force the regen of token for security
                    $url_ql = $user->ql('oc-panel',array( 'controller' => 'profile', 
                                                          'action'     => 'changepass'),TRUE);

                    //we don't use this since checks if the user is subscribed which is stupid since you want to remember your password.
                    //$ret = $user->email('auth-remember',array('[URL.QL]'=>$url_ql));
                    $ret = Email::content($user->email,$user->name,NULL,NULL,'auth-remember',array('[URL.QL]'=>$url_ql)); 

                    //email sent notify and redirect him
                    if ($ret)
                    {
                        Alert::set(Alert::SUCCESS, __('Email to recover password sent'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
                    }

				}
				else
				{
					Form::set_errors(array(__('User not in database')));
				}
				
			}
			else
			{
				Form::set_errors(array(__('Invalid Email')));
			}
			
		}
				
			
	}

    /**
     * Simple register for user
     *
     */
    public function action_register()
    {
        $this->template->content = View::factory('pages/auth/register');
        $this->template->content->msg = '';
        
        //if user loged in redirect home
        if (Auth::instance()->logged_in())
        {
            $this->redirect(Route::get('oc-panel')->uri());
        }
        //posting data so try to remember password
        elseif (core::post('email') AND CSRF::valid('register'))
        {
            $email = core::post('email');
                
            if (Valid::email($email,TRUE))
            {
                if (core::post('password1')==core::post('password2'))
                {
                    //check we have this email in the DB
                    $user = new Model_User();
                    $user = $user->where('email', '=', $email)
                            ->limit(1)
                            ->find();
            
                    if ($user->loaded())
                    {
                        Form::set_errors(array(__('User already exists')));
                    }
                    else
                    {
                        //creating the user
                        $user = Model_User::create_email($email,core::post('name'),core::post('password1'));
                        
                        //login the user
                        Auth::instance()->login(core::post('email'), core::post('password1'));
                        
                        Alert::set(Alert::SUCCESS, __('Welcome!'));
                        //login the user
                        $this->redirect(Core::post('auth_redirect',Route::url('oc-panel')));
                        
                    }
        
                }
                else
                {
                    Form::set_errors(array(__('Passwords do not match')));
                }
            }
            else
            {
                Form::set_errors(array(__('Invalid Email')));
            }
                
        }
    
        //template header
        $this->template->title            = __('Register new user');
		$this->template->meta_description = __('Create a new profile at').' '.Core::config('general.site_name');    
            
    }
    
	/**
	 *
	 * Quick login for users.
	 * Useful for confirmation emails, remember passwords etc...
	 */
	public function action_ql()
	{
		$ql = $this->request->param('id');
		$url = Auth::instance()->ql_login($ql);
		
		//not a url go to login!
		if ($url==FALSE)
		{
			$url = Route::url('oc-panel',array('controller' => 'auth', 
										  		'action'     => 'login'),'http');	
		}
		$this->redirect($url);
	}

}
