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

   /**
    * redirects to public profile, we use it so we can cache the view and redirect them
    * @return redirect 
    */ 
   public function action_public()
   {
        $this->request->redirect(Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname)));
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



    public function action_download()
    {
        $this->auto_render = FALSE;

        $order_id = $this->request->param('id',0);

        $user = Auth::instance()->get_user();

        $order = new Model_Order();

        $order->where('id_user','=',$user->id_user)
            ->where('id_order','=',$order_id)
            ->where('status', '=', Model_Order::STATUS_PAID)
            ->limit(1)
            ->find();
        if ($order->loaded())
        {
            $file = DOCROOT.'data/'.$order->product->file_name;
            if (is_readable($file) AND  !empty($order->product->file_name))
            {
                //create a download
                Model_Download::generate($user, $order);

                //how its called the downloaded file
                $file_name = $order->id_order.'-'.$order->product->seotitle.'-'.$order->product->version.strrchr($file, '.');

                $this->response->send_file($file,$file_name);
            }
        }
        
        Alert::set(Alert::ERROR, __('Download not found.'));
        $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
    
    }


    /**
     * action to download a free digital good, creates an order if needed and redirect to the payment
     * @return [type] [description]
     */
    public function action_free_download()
    {
        $this->auto_render = FALSE;

        $seotitle = $this->request->param('id');

        $product = new Model_product();
        $product->where('seotitle','=',$seotitle)
            ->where('status','=',Model_Product::STATUS_ACTIVE)
            ->limit(1)->find();

        if ($product->loaded())
        {
            $user = Auth::instance()->get_user();

            if ($product->final_price()>0)
            {
                Alert::set(Alert::ERROR, __('Not a free product.'));
                $this->request->redirect(Route::url('product',array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
            }
            else
            {

                //check if he has any other order with this product
                $order = new Model_Order();
                $order  ->where('id_user'   , '=', $user->id_user)
                        ->where('id_product', '=', $product->id_product)
                        ->where('status'    , '=', Model_Order::STATUS_PAID)
                        ->limit(1)->find();

                //not any we create the order
                if (!$order->loaded())
                    $order = Model_Order::sale(NULL,$user,$product,NULL,'free');

                $this->request->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order)));
            }
        }


    }

}
