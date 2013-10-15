<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Ad extends Auth_Controller {

   	/**
   	 * List all Advertisements (PUBLISHED)
   	 */
	public function action_index()
	{
		//template header
		$this->template->title           	= __('Advertisements');
		$this->template->meta_description	= __('Advertisements');
				
		$this->template->styles 			= array('/http://cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css' => 'screen');
		$this->template->scripts['footer'][]= '/js/oc-panel/moderation.js'; 


		//find all tables 
		
		$ads = new Model_Ad();
		
		if($this->request->query('define'))
		{
			if($this->request->query('define') == Model_Ad::STATUS_SPAM)
			{
				$ads = $ads->where('status', '=', Model_Ad::STATUS_SPAM); // display SPAM by overwriting query
			}
			elseif($this->request->query('define') == Model_Ad::STATUS_UNAVAILABLE)
			{
				$ads = $ads->where('status', '=', Model_Ad::STATUS_UNAVAILABLE); // display UNAVAILABLE by overwriting query	
			}
			elseif($this->request->query('define') == Model_Ad::STATUS_UNCONFIRMED)
			{
				$ads = $ads->where('status', '=', Model_Ad::STATUS_UNCONFIRMED); // display UNCONFIRMED (email activated ads) by overwriting query
			}
					
		}
		else $ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);
		
		$res_count = $ads->count_all();
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
    	    $ads = $ads->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
		
	        //find all tables 
	        $hits = new Model_Visit();
	        // $hits->find_all();

			$list_cat = Model_Category::get_all();
			$list_loc = Model_Location::get_all();


	       	$arr_hits = array(); // array of hit integers 
	       	
	        // fill array with hit integers 
	        foreach ($ads as $key_ads) {
	        	
	        	// match hits with ad
	        	$h = $hits->where('id_ad','=', $key_ads->id_ad);
	        	$count = count($h->find_all()); // count individual hits 
	        	array_push($arr_hits, $count);
	
	        }


			$this->template->content = View::factory('oc-panel/pages/ad',array('res'			=> $ads,
																				'pagination'	=> $pagination,
																				'category'		=> $list_cat,
																				'location'		=> $list_loc,
																				'hits'			=> $arr_hits)); // create view, and insert list with data

		}
		else
		{
			$this->template->content = View::factory('oc-panel/pages/ad', array('ads' => NULL));
		}		
	}

	/**
	 * Action MODERATION
	 */
	
	public function action_moderate()
	{
		//template header
		$this->template->title           	= __('Moderation');
		$this->template->meta_description	= __('Moderation');
				
		$this->template->styles 			= array('/http://cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= '/js/oc-panel/moderation.js'; 


		//find all tables 
		
		$ads = new Model_Ad();

		$res_count = $ads->where('status', '=', Model_Ad::STATUS_NOPUBLISHED)->count_all();
		
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
    	    $ads = $ads->where('ad.status', '=', Model_Ad::STATUS_NOPUBLISHED)
    	    					->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
		
	        //find all tables 
	        $hits = new Model_Visit();
	        $hits->find_all();

			$list_cat = Model_Category::get_all();
			$list_loc = Model_Location::get_all();

	       	$arr_hits = array(); // array of hit integers 
	       	
	        // fill array with hit integers 
	        foreach ($ads as $key_ads) {
	        	
	        	// match hits with ad
	        	$h = $hits->where('id_ad','=', $key_ads->id_ad);
	        	$count = count($h->find_all()); // count individual hits 
	        	array_push($arr_hits, $count);
	
	        }

			$this->template->content = View::factory('oc-panel/pages/moderate',array('ads'			=> $ads,
																					'pagination'	=> $pagination,
																					'category'		=> $list_cat,
																					'location'		=> $list_loc,
																					'hits'			=> $arr_hits)); // create view, and insert list with data

		}
		else
		{
			Alert::set(Alert::INFO, __('You do not have any advertisements waiting to be published'));
			$this->template->content = View::factory('oc-panel/pages/moderate', array('ads' => NULL));
		}
        
	} 

	/**
	 * Delete advertisement: Delete
     * @todo move to model ad
	 */
	public function action_delete()
	{
		$element = ORM::factory('ad', $this->request->param('id'));
		$id = $this->request->param('id');
		
		$format_id = explode('_', $id);

		if(Auth::instance()->logged_in() && Auth::instance()->get_user()->id_user == $element->id_user 
			|| Auth::instance()->logged_in() && Auth::instance()->get_user()->id_role == 10)
		{
			foreach ($format_id as $id) 
			{
				
				if (isset($id) AND $id !== '')
				{
					$this->auto_render = FALSE;
					$this->template = View::factory('js');
					$element = ORM::factory('ad', $id);
					
					if($element->loaded())
					{
						try
						{
							
							$img_path = $element->gen_img_path($element->id_ad, $element->created);
							

							if (!is_dir($img_path)) 
								$element->delete();	
							else
							{
								$element->delete_images($img_path);
								$element->delete();
							}
								
						}
						catch (Exception $e)
						{
							Alert::set(Alert::ALERT, __('Warning, something went wrong while deleting'));
							throw new HTTP_Exception_500($e->getMessage());
						}
					}	
				}
				
			}
			Alert::set(Alert::SUCCESS, __('Advertisement is deleted'));
			$param_current_url = $this->request->param('current_url');
		
			
			if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
				Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
			elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
				Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
			else
				Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);
		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->request->redirect(Route::url('default'));
		}
	}

	/**
	 * Mark advertisement as spam : STATUS = 30
	 */
	public function action_spam()
	{
		$id = $this->request->param('id');
		$param_current_url = $this->request->param('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{ 
			if (isset($id) AND $id !== '')
			{ 
				$spam_ad = ORM::factory('ad', $id);

				if ($spam_ad->loaded())
				{
					if ($spam_ad->status != 30)
					{
						$spam_ad->status = 30;
						
						try
						{
							$spam_ad->save();
						}
						catch (Exception $e)
						{
							throw new HTTP_Exception_500($e->getMessage());
						}
					}
					else
					{				
						Alert::set(Alert::ALERT, __('Warning, Advertisement is already marked as spam'));
						if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
						elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
						else
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);
					} 
				}
				else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			}
		}
		Alert::set(Alert::SUCCESS, __('Advertisement is marked as spam'));
		
		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);
	}


	/**
	 * Mark advertisement as deactivated : STATUS = 50
	 */
	public function action_deactivate()
	{

		$id = $this->request->param('id');
		$param_current_url = $this->request->param('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if (isset($id) AND $id !== '')
			{

				$deact_ad = ORM::factory('ad', $id);

				if ($deact_ad->loaded())
				{
					if ($deact_ad->status != 50)
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
						if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
						elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
						else
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);
					} 
				}
				else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			}
		}
		Alert::set(Alert::SUCCESS, __('Advertisement is deactivated'));
		
		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);

	}

	/**
	 * Mark advertisement as active : STATUS = 1
	 */
	
	public function action_activate()
	{

		$id = $this->request->param('id');
		$param_current_url = $this->request->param('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if (isset($id) AND $id !== '')
			{
				$active_ad = new Model_Ad($id);

				if ($active_ad->loaded())
				{
					if ($active_ad->status != 1)
					{
						$active_ad->published = Date::unix2mysql(time());
						$active_ad->status = Model_Ad::STATUS_PUBLISHED;
						
						try
						{
							$active_ad->save();

							//subscription is on
							$data = array(	'title' 		=> $title 		= 	$active_ad->title,
											'cat'			=> $cat 		= 	$active_ad->category,
											'loc'			=> $loc 		= 	$active_ad->location,	
										 );

							Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $active_ad->price)), $active_ad->seotitle, Auth::instance()->get_user()->email); // if subscription is on

						}
							catch (Exception $e)
						{
							throw new HTTP_Exception_500($e->getMessage());
						}
					}
					else
					{				
						Alert::set(Alert::ALERT, __("Warning, Advertisement is already marked as 'active'"));
						if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
						elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
						else
							Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);
					} 
				}
				else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			}
		}

		$this->multiple_mails($format_id); // sending many mails at the same time @TODO EMAIl

		if (Core::config('sitemap.on_post') == TRUE)
			Sitemap::generate();

		Alert::set(Alert::SUCCESS, __('Advertisement is active and published'));
			
		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);
	}

	/**
	 * Delete all ads in that category
	 *
	 * Depending on what status they have, it delets all of them
	 */
	public function action_delete_all()
	{
		$query = $this->request->query();

		$ads = new Model_Ad();
		$ads = $ads->where('status', '=', $query)->find_all();
	
		if (isset($ads))
		{
			try 
			{
				DB::delete('ads')->where('status', '=', $query)->execute();	
			} 
			catch (Exception $e) 
			{
				Alert::set(Alert::ALERT, __('Warning, something went wrong while deleting'));
				throw new HTTP_Exception_500($e->getMessage());
			}
		}

		if ($query['define'] == Model_Ad::STATUS_NOPUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($query['define'] == Model_Ad::STATUS_PUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$query['define']);
	}

	public function action_featured()
	{

		$id = $this->request->param('id');
		$param_current_url = $this->request->param('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if (isset($id) AND $id !== '')
			{
				$featured_ad = ORM::factory('ad', $id);

				if ($featured_ad->loaded())
				{
					
					if($featured_ad->featured == NULL)
					{
						$featured_ad->featured = Date::unix2mysql(time() + (core::config('payment.featured_days') * 24 * 60 * 60));
	        
				        try {
				            $featured_ad->save();
				        } catch (Exception $e) {
				 	          
				        }
				    }
				    else
					{				
						$featured_ad->featured = NULL;
						try {
				            $featured_ad->save();
				        } catch (Exception $e) {
				 	          
				        }
					} 
			    }
			    else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			    
			}
		}
		Alert::set(Alert::SUCCESS, __('Advertisement is featured'));
		
		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?define='.$param_current_url);

	}

	//temporary function until i figure out how to deal with mass mails @TODO EMAIL
	public function multiple_mails($receivers)
	{
	
		foreach ($receivers as $num => $receiver_id) {
			if(is_numeric($receiver_id))
			{
				$ad 		= new Model_Ad($receiver_id);
				$cat 		= new Model_Category($ad->id_category);
				$usr 		= new Model_User($ad->id_user);

				if($usr->loaded())
				{

					$edit_url = core::config('general.base_url').'oc-panel/profile/update/'.$ad->id_ad;
                    $delete_url = core::config('general.base_url').'oc-panel/ad/delete/'.$ad->id_ad;
					//we get the QL, and force the regen of token for security
					$url_ql = $usr->ql('ad',array( 'category' => $cat->seoname, 
				 	                                'seotitle'=> $ad->seotitle),TRUE);

					$ret = $usr->email('ads.activated',array('[USER.OWNER]'=>$usr->name,
															 '[URL.QL]'=>$url_ql,
															 '[AD.NAME]'=>$ad->title,
															 '[URL.EDITAD]'=>$edit_url,
                    										 '[URL.DELETEAD]'=>$delete_url));
					
				}	
			}
			
		}
		
	}

}
