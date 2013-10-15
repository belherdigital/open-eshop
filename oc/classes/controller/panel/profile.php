<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Profile extends Auth_Controller {

    

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

					try
					{
						$user->save();
					}
					catch (ORM_Validation_Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
					catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
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
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
            } 
            if(!Upload::size($image, core::config('image.max_image_size').'M'))
            {
                Alert::set(Alert::ALERT, $image['name'].' '.__('Is not of valid size. Size is limited on '.core::config('general.max_image_size').'MB per image'));
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
            }
            Alert::set(Alert::ALERT, $image['name'].' '.__('Image is not valid. Please try again.'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
        }
        else
        {
            if($image != NULL) // sanity check 
            {   
            	$user_id = Auth::instance()->get_user()->id_user;
                // saving/uploadng zip file to dir.
                $root = DOCROOT.'images/users/'; //root folder
            	$image_name = $user_id.'.png';
            	$width = core::config('image.width'); // @TODO dynamic !?
            	$height = core::config('image.height');// @TODO dynamic !?
            	$image_quality = core::config('image.quality');
                
                // if folder doesnt exists
               	if(!file_exists($root))
               		mkdir($root, 775, true);

                // save file to root folder, file, name, dir
                if($file = Upload::save($image, $image_name, $root))
                {
	                // resize uploaded image 
	                Image::factory($file)
                        ->resize($width, $height, Image::AUTO)
                        ->save($root.$image_name,$image_quality);

                }
                
                Alert::set(Alert::SUCCESS, $image['name'].' '.__('Image is uploaded.'));
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
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
			
			$user->name = core::post('name');
			$user->email = core::post('email');
			$user->seoname = URL::title(core::post('name'));
			// $user->password2 = core::post('password2');
			
			$password1 = core::post('password1');
			if(!empty($password1))
			{
				if(core::post('password1') == core::post('password2'))
				{
					$user->password = core::post('password1');
				}
				else
				{
					Alert::set(Alert::ERROR, __('New password is invalid, or they do not match! Please try again.'));
					$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
				}
			} 

			try {
				$user->save();
				Alert::set(Alert::SUCCESS, __('You have successfuly changed your data'));
				$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
				
			} catch (Exception $e) {
				//throw 500
				throw new HTTP_Exception_500();
			}	
		}
	}

	public function action_ads()
	{
		$cat = new Model_Category();
		$list_cat = $cat->find_all(); // get all to print at sidebar view
		
		$loc = new Model_Location();
		$list_loc = $loc->find_all(); // get all to print at sidebar view

		$user = Auth::instance()->get_user();
		$ads = new Model_Ad();

		$my_adverts = $ads->where('id_user', '=', $user->id_user);

		$res_count = $my_adverts->count_all();
		
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('general.advertisements_per_page')
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                 
    	    ));

    	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__("My Advertisement page ").$pagination->current_page));
    	    $ads = $my_adverts->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();


          	$this->template->content = View::factory('oc-panel/profile/ads', array('ads'=>$ads,
          																		   'pagination'=>$pagination,
          																		   'category'=>$list_cat,
          																		   'location'=>$list_loc,
          																		   'user'=>$user));
        }
        else
        {

        	$this->template->content = View::factory('oc-panel/profile/ads', array('ads'=>$ads,
          																		   'pagination'=>NULL,
          																		   'category'=>NULL,
          																		   'location'=>NULL,
          																		   'user'=>$user));
        }
	}

	/**
	 * Mark advertisement as deactivated : STATUS = 50
	 */
	public function action_deactivate()
	{

		$id = $this->request->param('id');
		
		
		if (isset($id))
		{

			$deact_ad = new Model_Ad($id);

			if ($deact_ad->loaded())
			{
				if(Auth::instance()->get_user()->id_user !== $deact_ad->id_user OR 
					(Auth::instance()->get_user()->id_role !== Model_Role::ROLE_ADMIN AND Auth::instance()->get_user()->id_user == 1))

                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                    Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
                }
                elseif ($deact_ad->status != 50)
				{
					$deact_ad->status = 50;
					
					try
					{
						$deact_ad->save();
					}
						catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
				}
				else
				{				
					Alert::set(Alert::ALERT, __("Warning, Advertisement is already marked as 'deactivated'"));
					Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
				} 
			}
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
		}
		
		Alert::set(Alert::SUCCESS, __('Advertisement is deactivated'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
	}

	/**
	 * Mark advertisement as active : STATUS = 1
	 */
	
	public function action_activate()
	{

		$id = $this->request->param('id');
		
		if (isset($id))
		{
			$active_ad = new Model_Ad($id);

			if ($active_ad->loaded())
			{
				if(Auth::instance()->get_user()->id_user !== $active_ad->id_user OR 
					(Auth::instance()->get_user()->id_role !== Model_Role::ROLE_ADMIN AND Auth::instance()->get_user()->id_user == 1))
                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                    Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
                }
				elseif ($active_ad->status != 1)
				{
					$active_ad->published = Date::unix2mysql(time());
					$active_ad->status = 1;
					
					try
					{
						$active_ad->save();
					}
						catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
				}
				else
				{				
					Alert::set(Alert::ALERT, __("Advertisement is already marked as 'active'"));
					Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
				} 
			}
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
		}
		

		// send confirmation email
		$cat = new Model_Category($active_ad->id_category);
		$usr = new Model_User($active_ad->id_user);
		if($usr->loaded())
		{
			$edit_url = core::config('general.base_url').'oc-panel/profile/update/'.$active_ad->id_ad;
            $delete_url = core::config('general.base_url').'oc-panel/ad/delete/'.$active_ad->id_ad;

			//we get the QL, and force the regen of token for security
			$url_ql = $usr->ql('ad',array( 'category' => $cat->seoname, 
		 	                                'seotitle'=> $active_ad->seotitle),TRUE);

			$ret = $usr->email('ads.activated',array('[USER.OWNER]'=>$usr->name,
													 '[URL.QL]'=>$url_ql,
													 '[AD.NAME]'=>$active_ad->title,
													 '[URL.EDITAD]'=>$edit_url,
                    								 '[URL.DELETEAD]'=>$delete_url));	
		}	

		if (Core::config('sitemap.on_post') == TRUE)
			Sitemap::generate();

		Alert::set(Alert::SUCCESS, __('Advertisement is active and published'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
	}

	/**
	 * Edit advertisement: Update
	 *
	 * All post fields are validated
	 */
	public function action_update()
	{
		//template header
		$this->template->title           	= __('Edit advertisement');
		$this->template->meta_description	= __('Edit advertisement');
		
		$this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                   'js/jquery.validate.min.js',
                                                   'js/oc-panel/edit_ad.js');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		 	

		$form = new Model_Ad($this->request->param('id'));
		
		//find all, for populating form select fields 
		list($categories,$order_categories)  = Model_Category::get_all();

		list($locations,$order_locations)  = Model_Location::get_all();

	
		if(Auth::instance()->logged_in() && Auth::instance()->get_user()->id_user == $form->id_user 
			|| Auth::instance()->logged_in() && Auth::instance()->get_user()->id_role == 10)
		{
			$extra_payment = core::config('payment');
			
			Breadcrumbs::add(Breadcrumb::factory()->set_title("Update"));
			$this->template->content = View::factory('oc-panel/profile/edit_ad', array('ad'					=>$form, 
																					   'locations'			=>$locations,
																					   'order_locations'  	=>$order_locations, 
																					   'categories'			=>$categories,
																					   'order_categories'	=>$order_categories,
																					   'extra_payment'		=>$extra_payment,
																					   'fields'             => Model_Field::get_all()));
		
			if ($this->request->post())
			{
				$cat = new Model_Category();
				$loc = new Model_Location();

				// deleting single image by path 
				$deleted_image = core::post('img_delete');
				if($deleted_image)
				{
					$img_path = $form->gen_img_path($form->id_ad, $form->created);
					
					if (!is_dir($img_path)) 
					{
						return FALSE;
					}
					else
					{	
					
						//delete formated image
						unlink($img_path.$deleted_image.'.jpg');

						//delete original image
						$orig_img = str_replace('thumb_', '', $deleted_image);
						unlink($img_path.$orig_img.".jpg");

						$this->request->redirect(Route::url('oc-panel', array('controller'	=>'profile',
																			  'action'		=>'update',
																			  'id'			=>$form->id_ad)));
					}
				}// end of img delete

				$data = array(	'_auth' 		=> $auth 		= 	Auth::instance(),
								'title' 		=> $title 		= 	Model_Ad::banned_words(core::post('title')),
								'seotitle' 		=> $seotitle 	= 	core::post('title'),
								'cat'			=> $category 	= 	core::post('category'),
								'loc'			=> $loc 		= 	core::post('location'),
								'description'	=> $description = 	Model_Ad::banned_words(core::post('description')),
								'price'			=> $price 		= 	floatval(str_replace(',', '.', core::post('price'))),
								// 'status'		=> $status		= 	core::post('status'),
								'address'		=> $address 	= 	core::post('address'),
								'website'		=> $website 	= 	core::post('website'),
								'phone'			=> $phone 		= 	core::post('phone'),
								'has_images'	=> 0,
								'user'			=> $user 		= new Model_User()
								); 

				// append to $data new custom values
	            foreach ($_POST as $name => $field) 
	            {
	            	// get by prefix
					if (strpos($name,'cf_') !== false) 
					{
						$data[$name] = $field;
						//checkbox and radio when selected return string 'on' as a value
						if($field == 'on')
						{
							$data[$name] = 1;
						}
					}
	        	}

				//insert data
				if (core::post('title') != $form->title)
				{
					if($form->has_images == 1)
					{
						$current_path = $form->gen_img_path($form->id_ad, $form->created);
						// rename current image path to match new seoname
						rename($current_path, $form->gen_img_path($form->id_ad, $form->created)); 

					}
					$seotitle = $form->gen_seo_title($data['title']);
					$form->seotitle = $seotitle;
					
				}
				else 
					$form->seotitle = $form->seotitle;
				 
				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				// $form->status 			= $data['status'];	
				$form->price 			= $data['price']; 								
				$form->address 			= $data['address'];
				$form->website 			= $data['website'];
				$form->phone			= $data['phone']; 

				// set custom values
				foreach ($data as $key => $value) 
	            {
	            	// get only custom values with prefix
					if (strpos($key,'cf_') !== false) 
					{
						$form->$key = $value;
					}
	        	}

				$obj_ad = new Model_Ad();

				// image upload
				$error_message = NULL;
	    		$filename = NULL;

    			if (isset($_FILES['image0']) && count($obj_ad->get_images()) <= core::config('advertisement.num_images'))
        		{
	        		$img_files = array($_FILES['image0']);
	            	$filename = $obj_ad->save_image($img_files, $form->id_ad, $form->created, $form->seotitle);
        		}
        		if ( $filename == TRUE)
	       		{
		        	$form->has_images = 1;
	        	}

	        	try 
	        	{
	        		
	        		// if user changes category, do payment first
	        		// moderation 2 -> payment on, moderation 5 -> payment with moderation
	        		// data['cat'] -> category selected , last_known_ad->id_category -> obj of current ad (before save) 
	        		$moderation = core::config('general.moderation');
	        		$last_known_ad = $obj_ad->where('id_ad', '=', $this->request->param('id'))->limit(1)->find();
	        		if($moderation == Model_Ad::PAYMENT_ON || $moderation == Model_Ad::PAYMENT_MODERATION)
	        		{
	        			// PAYMENT METHOD ACTIVE
						$payment_order = new Model_Order();
						$advert_have_order = $payment_order->where('id_ad', '=', $this->request->param('id'));
						   
	        			if($data['cat'] == $last_known_ad->id_category) // user didn't changed category 
	        			{
	        				// check if he payed when ad was created (is successful), 
	        				// if not give him alert that he didn't payed, and ad will not be published until he do  
							$cat_check = $cat->where('id_category', '=', $last_known_ad->id_category)->limit(1)->find(); // current category
							$advert_have_order->and_where('description', '=', $cat_check->seoname)->limit(1)->find();
							if($advert_have_order->loaded()) // if user have order
							{

								if($advert_have_order->status != Model_Order::STATUS_PAID)
								{ // order is not payed,  
									$form->status = 0;
									Alert::set(Alert::INFO, __('Advertisement is updated, but it won\'t be published until payment is done.'));
								}
								else // order is payed, update status and publish 
								{
									if($moderation == Model_Ad::PAYMENT_ON)
									{
										$form->status = 1;
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));	
									}
									else if($moderation == 5)
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));
								}
							}
							$form->save();
	        				$this->request->redirect(Route::url('oc-panel', array('controller'	=>'profile',
																				  'action'		=>'update',
																				  'id'			=>$form->id_ad)));
						
	        			} // end - same category
	        			else // different category
	        			{ 
	        				// user have pending order with new category(possible that he previously tried to do the same action)
	        				
							$cat_check = $cat->where('id_category', '=', $data['cat'])->limit(1)->find(); // newly selected category
							$advert_have_order->and_where('description', '=', $cat_check->seoname)->limit(1)->find();
	        				if($advert_have_order->loaded())
	        				{
	        					// sanity check -> we don't want to charge him twice for same category 
	        					if($advert_have_order->status != Model_Order::STATUS_PAID)
	        						$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $advert_have_order->id_order))); 	
								else // order is payed, update status and publish 
								{
									if($moderation == Model_Ad::PAYMENT_ON)
									{
										$form->status = 1;
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));	
									}
									else if($moderation == Model_Ad::PAYMENT_MODERATION)
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));
									
								}
								$form->save();
	        				}
	        				else // user doesn't have order -> create new order and redirect him to payment (do not update status until payment is confirmed)
	        				{
	        					$order_id = $payment_order->make_new_order($data, Auth::instance()->get_user()->id_user, $form->seotitle);
	        					
	        					if($order_id == NULL) // this is the case when in make_new_order we detect that category OR category_parent doesn't have price
								{
									if($moderation == Model_Ad::PAYMENT_ON) // publish
										$form->status = 1;
								}
								else
								{
									// redirect to payment
				        			$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order_id))); // @TODO - check route	
								}
								$form->save();								
	        				}	
	        			}
	        		}
	        		
	        		// save ad
	        		$form->status = $last_known_ad->status;
	        		$form->save();
	        		Alert::set(Alert::SUCCESS, __('Advertisement is updated'));

	        		$this->request->redirect(Route::url('oc-panel', array('controller'	=>'profile',
																		  'action'		=>'update',
																		  'id'			=>$form->id_ad)));
	        	} catch (Exception $e) {
	 				//throw 500
					throw new HTTP_Exception_500($e->getMessage());       		
	        	}

	        	
			}
		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->request->redirect(Route::url('default'));
		}
	}

	public function action_stats()
   	{
   
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));

        $this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/oc-panel/stats/dashboard.js');
        
        $this->template->title = __('Stats');
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/profile/stats');

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

        // user and his ads
        $user = Auth::instance()->get_user();
        $ads = new Model_Ad();
        $collection_of_user_ads = $ads->where('id_user', '=', $user->id_user)->find_all();

        $list_ad = array();
        foreach ($collection_of_user_ads as $key) {
        	// make a list of ads (array), and than pass this array to query (IN).. To get correct visits
        	$list_ad[] = $key->id_ad;
        }
        
        // if user doesn't have any ads
       	if(empty($list_ad))
        	$list_ad = array(NULL);
        
        /////////////////////CONTACT STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $contacts_dates = $query->as_array('date');

        //Today 
        $query = DB::select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_today     = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $contacts = $query->as_array();
        $content->contacts_yesterday = (isset($contacts[0]['count']))?$contacts[0]['count']:0; //

        //Last 30 days contacts
        $query = DB::select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_month = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        //total contacts
        $query = DB::select(DB::expr('COUNT(contacted) count'))
        				->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->from('visits')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_total = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        /////////////////////VISITS STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $visits = $query->as_array('date');
 
        $stats_daily = array();
        foreach ($dates as $date) 
        {
            $count_contants = (isset($contacts_dates[$date['date']]['count']))?$contacts_dates[$date['date']]['count']:0;
            $count_visits = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;
            
            $stats_daily[] = array('date'=>$date['date'],'views'=> $count_visits, 'contacts'=>$count_contants);
        } 

        $content->stats_daily = $stats_daily;

        //Today 
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_today     = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $visits = $query->as_array();
        $content->visits_yesterday = (isset($visits[0]['count']))?$visits[0]['count']:0;


        //Last 30 days visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->where('id_ad', 'in', $list_ad)
                        ->from('visits')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;
        
   }

   /**
    * list all subscription for a given user
    * @return view 
    */ 
   public function action_subscriptions()
   {
   		$subscriptions = new Model_Subscribe();

   		$user = Auth::instance()->get_user()->id_user;

		//get all for this user
		$query = $subscriptions->where('id_user','=',$user)
							   ->find_all();

   		if(count($query) != 0)
   		{
   			// get categories, location, date, and price range to show in view 					   
   			

			$subs = $query->as_array();
			foreach ($subs as $s) 
			{

				$min_price = $s->min_price;
				$max_price = $s->max_price;
				$created   = $s->created;

				$category = new Model_Category($s->id_category);
				$location = new Model_Location($s->id_location);

				$list[] = array('min_price'=>$min_price,
								'max_price'=>$max_price,
								'created'=>$created,
								'category'=>$category->name,
								'location'=>$location->name,
								'id'=>$s->id_subscribe);
			}
			
			$this->template->content = View::factory('oc-panel/profile/subscriptions', array('list'=>$list));
   		}
   		else
   		{
   			Alert::set(Alert::INFO, __('No Subscriptions'));
   		}
    }

	public function action_unsubscribe()
	{
		$id_subscribe = $this->request->param('id');

		$subscription = new Model_Subscribe($id_subscribe);

		if($subscription->loaded())
		{
			try 
			{
				$subscription->delete();
				Alert::set(Alert::SUCCESS, __('You are unsubscribed'));
				$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'subscriptions')));
			} 
			catch (Exception $e) 
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
		}
	}

   /**
    * redirects to public profile, we use it so we can cache the view and redirect them
    * @return redirect 
    */ 
   public function action_public()
   {
        $this->request->redirect(Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname)));
   }


}
