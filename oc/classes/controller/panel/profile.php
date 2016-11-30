<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Profile extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Profile'))->set_url(Route::url('oc-panel',array('controller'=>'profile'))));

    }

	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home')));
		
		$this->template->title = __('Home');
		//$this->template->scripts['footer'][] = 'js/user/index.js';
		$this->template->content = View::factory('oc-panel/home-user');
	}


	public function action_changepass()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Change password')));
		
		$this->template->title   = __('Change password');

		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user));
		$this->template->content->msg ='';

		if ($this->request->post())
		{
			$user = Auth::instance()->get_user();
			
			if (core::post('password1')==core::post('password2'))
			{
				$new_pass = core::post('password1');
				if(!empty($new_pass)){

					$user->password = core::post('password1');
                    $user->last_modified = Date::unix2mysql();

					try
					{
						$user->save();
					}
					catch (ORM_Validation_Exception $e)
                    {
                        Form::set_errors($e->errors(''));
                    }
					catch (Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}

					Alert::set(Alert::SUCCESS, __('Password is changed'));
				}
				else
				{
					Form::set_errors(array(__('Nothing is provided')));
				}
			}
			else
			{
				Form::set_errors(array(__('Passwords do not match')));
			}
			
		}

	  
	}

	public function action_image()
	{
        if (Core::post('photo_delete') AND Auth::instance()->get_user()->delete_image()==TRUE )
        {
            Alert::set(Alert::SUCCESS, __('Photo deleted.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));

        }// end of photo delete
        
		//get image
		$image = $_FILES['profile_image']; //file post

        if ( 
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, explode(',',core::config('image.allowed_formats'))) OR
            ! Upload::size($image, core::config('image.max_image_size').'M'))
        {
        	if ( Upload::not_empty($image) && ! Upload::type($image, explode(',',core::config('image.allowed_formats'))))
            {
                Alert::set(Alert::ALERT, $image['name'].' '.__('Is not valid format, please use one of this formats "jpg, jpeg, png"'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
            } 
            if(!Upload::size($image, core::config('image.max_image_size').'M'))
            {
                Alert::set(Alert::ALERT, $image['name'].' '.__('Is not of valid size. Size is limited on '.core::config('general.max_image_size').'MB per image'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
            }
            Alert::set(Alert::ALERT, $image['name'].' '.__('Image is not valid. Please try again.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
        }
        else
        {
            if($image != NULL) // sanity check 
            {   
            	$user = Auth::instance()->get_user();
                // saving/uploadng zip file to dir.
                $root = DOCROOT.'images/users/'; //root folder
            	$image_name = $user->id_user.'.png';
            	$width = core::config('image.width'); // @TODO dynamic !?
            	$height = core::config('image.height');// @TODO dynamic !?
            	$image_quality = core::config('image.quality');
                

                // if folder does not exist, try to make it
                if ( ! is_dir($root) AND ! @mkdir($root, 0775, TRUE)) { // mkdir not successful ?
                    Alert::set(Alert::ERROR, __('Image folder is missing and cannot be created with mkdir. Please correct to be able to upload images.'));
                    return FALSE; // exit function
                };

                // save file to root folder, file, name, dir
                if($file = Upload::save($image, $image_name, $root))
                {
	                // resize uploaded image 
	                Image::factory($file)
                        ->orientate()
                        ->resize($width, $height, Image::AUTO)
                        ->save($root.$image_name,$image_quality);

                     // update category info
                    $user->has_image = 1;
                    $user->last_modified = Date::unix2mysql();
                    $user->save();

                    Alert::set(Alert::SUCCESS, $image['name'].' '.__('Image is uploaded.'));   
                }
                else
                    Alert::set(Alert::ERROR, $image['name'].' '.__('Icon file could not been saved.'));
                
                
                $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
            }
            
        }
	}

    public function action_edit()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit profile')));
        // $this->template->title = $user->name;
        //$this->template->meta_description = $user->name;//@todo phpseo
        $user = Auth::instance()->get_user();

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user));
        // $this->template->content = View::factory('pages/useredit',array('user'=>$user, 'captcha_show'=>$captcha_show));

        if($this->request->post())
        {
            //change elastic email status, he was subscribed but not anymore
            if ( Core::config('email.elastic_listname')!=''  AND $user->subscriber == 1 AND core::post('subscriber',0) == 0 )
                ElasticEmail::unsubscribe(Core::config('email.elastic_listname'),$user->email);
            elseif ( Core::config('email.elastic_listname')!=''  AND $user->subscriber == 0 AND core::post('subscriber',0) == 1 )
                ElasticEmail::subscribe(Core::config('email.elastic_listname'),$user->email,$user->name);
            
            $user->name = core::post('name');
            $user->email = core::post('email');
            $user->paypal_email = core::post('paypal_email');
            $user->signature = core::post('signature');
            $user->subscriber = core::post('subscriber',0);
            $user->description = core::post('description');
            //$user->seoname = $user->gen_seo_title(core::post('name'));
            $user->last_modified = Date::unix2mysql();

            try {
                $user->save();
                Alert::set(Alert::SUCCESS, __('You have successfuly changed your data'));  

            }
            catch (ORM_Validation_Exception $e)
            {
                Form::set_errors($e->errors(''));
            }
            catch (Exception $e) {
                //throw 500
                throw HTTP_Exception::factory(500,$e->getMessage());
            }   

            $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
        }
    }

    public function action_billing()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Billing Information')));
        
        $this->template->title   = __('Billing Information');

        $user = Auth::instance()->get_user();

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user));
        $this->template->content->msg ='';

        if ($this->request->post())
        {
            $user = Auth::instance()->get_user();
            
            $user->country       = core::post('country');
            $user->city          = core::post('city');
            $user->postal_code   = core::post('postal_code');
            $user->address       = core::post('address');
            $user->last_modified = Date::unix2mysql();
            $user->VAT_number    = core::post('VAT_number');

            //theres VAT sent
            if (core::post('VAT_number')!=NULL)
            {
                //if VAT submited and country is from EU verify it, not valid do not store it and display on page
                if (!euvat::verify_vies(core::post('VAT_number'),$user->country))
                {
                    Alert::set(Alert::ERROR, __('Invalid EU Vat Number, please verify number and country match'));
                    $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'billing')).'?order_id='.core::request('order_id').'');
                }
            }

            //save user data
            try
            {
                $user->save();
                Alert::set(Alert::SUCCESS, __('Billing information changed'));
            }
            catch (ORM_Validation_Exception $e)
            {
                Form::set_errors($e->errors(''));
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            } 

            //in case there was an order rediret him to checkout
            if (is_numeric(core::request('order_id')))
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>core::request('order_id'))));
            
        }
      
    }
   /**
    * redirects to public profile, we use it so we can cache the view and redirect them
    * @return redirect 
    */ 
   public function action_public()
   {
        $this->redirect(Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname)));
   }


   public function action_orders()
   {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Purchases')));
        $this->template->title   = __('Purchases');

        $user = Auth::instance()->get_user();

        $orders = new Model_Order();

        $orders = $orders->where('id_user','=',$user->id_user)
                        ->where('status', '=', Model_Order::STATUS_PAID)
                        ->order_by('created','desc')
                        ->find_all();

        $licenses = new Model_License();
        $licenses = $licenses->where('id_user','=',$user->id_user)
                        ->find_all();


        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/orders',array('licenses'=>$licenses,'orders'=>$orders));
   }

   public function action_order()
   {    
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My Purchases'))->set_url(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Print')));
        $this->template->title   = __('Print Order');

        $user = Auth::instance()->get_user();
        $id_order = $this->request->param('id');
        
        $order = new Model_Order;
        $order->where('id_order', '=', $id_order);

        //if admin we do not verify the user
        if ($user->id_role!=Model_Role::ROLE_ADMIN)
            $order->where('id_user','=',$user->id_user);

        $order->find();

        if( ! $order->loaded() )
        {
            Alert::set(ALERT::WARNING, __('Order could not be loaded'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
        }


        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/order');

        $content->order = $order;
        $content->product = $order->product;
        $content->user = $user;

        if(core::get('print') == 1)
        {
            $this->template->scripts['footer'] = array('js/oc-panel/order.js');
        }
        
   }

   /**
    * returns file attached to the order, if theres file...
    * @return void 
    */
    public function action_download()
    {
        $this->auto_render = FALSE;
        $err_msg = __('Download not found.');

        $order_id = $this->request->param('id',0);
        $user = Auth::instance()->get_user();

        $order = new Model_Order();
        $order->where('id_user','=',$user->id_user)
            ->where('id_order','=',$order_id)
            ->where('status', '=', Model_Order::STATUS_PAID)
            ->limit(1)
            ->find();
        if ($order->loaded())
            $err_msg = $order->download();
        
        Alert::set(Alert::ERROR, $err_msg);
        $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
    
    }


    /**
     * user ads a new review
     * @return [type] [description]
     */
    public function action_review()
    {
        $id_order = $this->request->param('id');

        $user = Auth::instance()->get_user();

        $order = new Model_Order();
        $order->where('id_user','=',$user->id_user)
            ->where('id_order','=',$id_order)
            ->where('status', '=', Model_Order::STATUS_PAID)
            ->limit(1)
            ->find();

        if ($order->loaded())
        {
            $product = $order->product;

            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Purchases'))->set_url(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Review').' '.$product->title));
            $this->template->title   = __('Review product').' '.$product->title;
            $this->template->scripts['footer'][] = 'js/jquery.raty.min.js';
            $this->template->scripts['footer'][] = 'js/oc-panel/review.js';

            //lets see if we had the review already done..
            $review = new Model_Review();
            $review->where('id_user','=',$user->id_user)
                ->where('id_product','=',$product->id_product)
                ->where('id_order','=',$order->id_order)
                ->where('status', '=', Model_Review::STATUS_ACTIVE)
                ->limit(1)
                ->find();
            
            $this->template->bind('content', $content);
            
            $errors = NULL;
            if($this->request->post() AND !$review->loaded())  
            {
                $validation = Validation::factory($this->request->post())->rule('rate', 'numeric')
                                                ->rule('description', 'not_empty')->rule('description', 'min_length', array(':value', 5))
                                                ->rule('description', 'max_length', array(':value', 1000));
                if ($validation->check())
                {
                    $rate = core::post('rate');
                    if ($rate>Model_Review::RATE_MAX)
                        $rate = Model_Review::RATE_MAX;
                    elseif ($rate<0)
                        $rate = 0;

                    $review = new Model_Review();
                    $review->id_user        = $user->id_user;
                    $review->id_order       = $order->id_order;
                    $review->id_product     = $product->id_product;
                    $review->description    = core::post('description');
                    $review->status         = Model_Review::STATUS_ACTIVE;
                    $review->ip_address     = ip2long(Request::$client_ip);
                    $review->rate           = $rate;
                    $review->save();
                    //email product owner?? notify him of new review
                    $product->user->email('review-product',
                                 array('[TITLE]'        =>$product->title,
                                        '[RATE]'        =>$review->rate,
                                        '[DESCRIPTION]' =>$review->description,
                                        '[URL.QL]'      =>$product->user->ql('product-review',array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))));

                    $product->recalculate_rate();
                    Alert::set(Alert::SUCCESS, __('Thanks for your review!'));
                    $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
                }
                else
                {
                    $errors = $validation->errors('ad');
                    foreach ($errors as $f => $err) 
                    {
                        Alert::set(Alert::ALERT, $err);
                    }
                }
               
            }     
            
            $this->template->content = View::factory('oc-panel/profile/review',array('order'=>$order,'product'=>$product,'errors'=>$errors,'review'=>$review));
        }
        else
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));

    }


    //affiliate panel for the users
    public function action_affiliate()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Affiliate')));
        $this->template->title   = __('Affiliate Panel');

        $user = Auth::instance()->get_user();

        //Hack so the admin can see the stats for any user! cool!
        if ($user->id_role==Model_Role::ROLE_ADMIN)
        {
            $id_user = $this->request->param('id');
            if (is_numeric($id_user))
                $user = new Model_User($id_user);
        }

        $this->template->styles = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/stats/dashboard.js');

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/affiliate');
        $content->user = $user;
        
        // list of all products to build affiliate links
        $products = new Model_Product();
        $products = $products->where('status','=',Model_Product::STATUS_ACTIVE)->find_all();

        $content->products = $products;

        //change paypal account->profile, put a warning if he didnt set it yet with a link
        if (!valid::email($user->paypal_email))
            Alert::set(Alert::INFO, __('Please set your paypal email at your profile'));
        
        //list all his payments->orders paid
        $payments = new Model_Order();
        $content->payments = $payments->where('id_user','=',$user->id_user)
                        ->where('id_product','is',NULL)
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->order_by('pay_date','DESC')->find_all();
                        
        //see stats
        ////////////////////
        //total earned commissions
        $query = DB::select(DB::expr('SUM(amount) total'))
                        ->from('affiliates')
                        ->where('id_user','=',$user->id_user)
                        ->group_by('id_user')
                        ->execute();

        $total_earnings = $query->as_array();
        $content->total_earnings = (isset($total_earnings[0]['total']))?$total_earnings[0]['total']:0;
        
        //total since last payment
        $last_payment_date = DB::select('pay_date')
                        ->from('orders')
                        ->where('id_user','=',$user->id_user)
                        ->where('id_product','is',NULL)
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->order_by('pay_date','ASC')
                        ->limit(1)->execute();

        $last_payment_date = $last_payment_date->as_array();
        $content->last_payment_date = (isset($last_payment_date[0]['pay_date']))?$last_payment_date[0]['pay_date']:NULL;
        $content->last_earnings = 0;
        if ($content->last_payment_date!=NULL)
        {
            //commissions since last payment
            $query = DB::select(DB::expr('SUM(amount) total'))
                            ->from('affiliates')
                            ->where('id_user','=',$user->id_user)
                            ->where('created','between',array($content->last_payment_date,Date::unix2mysql()))
                            ->where('status','=',Model_Affiliate::STATUS_CREATED)
                            ->group_by('id_user')
                            ->execute();

            $last_earnings = $query->as_array();
            $content->last_earnings = (isset($last_earnings[0]['total']))?$last_earnings[0]['total']:0;
        }
        
        //due to pay, is commisions with to pay date bigger than today
        //commissions due to pay
        $query = DB::select(DB::expr('SUM(amount) total'))
                        ->from('affiliates')
                        ->where('id_user','=',$user->id_user)
                        ->where('date_to_pay','<',Date::unix2mysql())
                        ->where('status','=',Model_Affiliate::STATUS_CREATED)
                        ->group_by('id_user')
                        ->execute();

        $due_to_pay = $query->as_array();
        $content->due_to_pay = (isset($due_to_pay[0]['total']))?$due_to_pay[0]['total']:0;
        
        //Getting the dates and range
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        //mysql formated dates
        $my_from_date = Date::unix2mysql($from_date);
        $my_to_date   = Date::unix2mysql($to_date);

        //dates range we are filtering
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');

        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('id_affiliate','=',$user->id_user)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $visits = $query->as_array('date');

        //commissions created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('affiliates')
                        ->where('id_user','=',$user->id_user)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $earnings = $query->as_array('date');

        $stats_daily = array();
        foreach ($dates as $date) 
        {
            $count_views = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;  
            $earned      = (isset($earnings[$date['date']]['total']))?$earnings[$date['date']]['total']:0;  
            $stats_daily[] = array('date'=>$date['date'],'views'=> $count_views,'$'=>$earned);
        } 

        $content->stats_daily =  $stats_daily;

        ////////////////////////////////////////////////
        
        
        //list paginated with commissions
        /////////////////////////////////
        $commissions = new Model_Affiliate();

        $commissions = $commissions->where('id_user','=',$user->id_user);

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $commissions->count_all(),
                    'items_per_page' => 100,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
                    'id'         => $this->request->param('id'),
        ));

        $pagination->title($this->template->title);

        $commissions = $commissions->order_by('created','desc')
                        ->limit($pagination->items_per_page)
                        ->offset($pagination->offset)
                        ->find_all();

        $pagination = $pagination->render(); 
        $content->pagination  = $pagination;
        $content->commissions = $commissions;
        //////////////////////////////////


        
    }

    //2 step auth verification code generation
    public function action_2step()
    {
        $action = $this->request->param('id');


        if ($action == 'enable')
        {
            //load library
            require Kohana::find_file('vendor', 'GoogleAuthenticator');
            $ga = new PHPGangsta_GoogleAuthenticator();
            $this->user->google_authenticator = $ga->createSecret();

            //set cookie
            Cookie::set('google_authenticator' , $this->user->id_user, Core::config('auth.lifetime') );

            Alert::set(Alert::SUCCESS, __('2 Step Authentication Enabled'));
        }
        elseif($action == 'disable')
        {
            $this->user->google_authenticator = '';
            Cookie::delete('google_authenticator');
            Alert::set(Alert::INFO, __('2 Step Authentication Disabled'));
        }

        try {
            $this->user->save();
        } catch (Exception $e) {
            //throw 500
            throw HTTP_Exception::factory(500,$e->getMessage());
        }   

        $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
    }
}
