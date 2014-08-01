<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Category extends Auth_Crud {
	
	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('name','order', 'id_category', 'id_category_parent');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'category';	


    /**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_index($view = NULL)
    {
        //HTTP::redirect(Route::url('oc-panel',array('controller'  => 'category','action'=>'dashboard')));  
        //template header
        $this->template->title  = __('Categories');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Categories')));
        $this->template->styles  = array('css/sortable.css' => 'screen', 
        'http://cdn.jsdelivr.net/bootstrap.tagsinput/0.3.9/bootstrap-tagsinput.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/categories.js';
        $this->template->scripts['footer'][] = 'http://cdn.jsdelivr.net/bootstrap.tagsinput/0.3.9/bootstrap-tagsinput.min.js';

        $cats  = Model_Category::get_as_array();
        $order = Model_Category::get_multidimensional();


        $this->template->content = View::factory('oc-panel/pages/categories',array('cats' => $cats,'order'=>$order));
    }

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {

        $this->template->title = __('New').' '.__($this->_orm_model);
        
        $form = new FormOrm($this->_orm_model);
            
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->object->description = Kohana::$_POST_ORIG['formorm']['description'];
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
                    .__('Delete All').'</a>');
            
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else 
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/pages/categories/create', array('form' => $form));
    }

    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');
    
        $form = new FormOrm($this->_orm_model,$this->request->param('id'));
		$category = new Model_Category($this->request->param('id'));
        
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                if ($form->object->id_category == $form->object->id_category_parent)
                {
                    Alert::set(Alert::INFO, __('You can not set as parent the same category'));
                    $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$form->object->id_category)));
                }
                $form->object->description = Kohana::$_POST_ORIG['formorm']['description'];
                $form->save_object();
                $form->object->parent_deep =  $form->object->get_deep();
                $form->object->save();
                Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
                    .__('Delete All').'</a>');
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/pages/categories/update', array('form' => $form, 'category' => $category));
    }

    /**
     * saves the category in a specific order and change the parent
     * @return void 
     */
    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $cat = new Model_Category(core::get('id_category'));

        if ($cat->loaded())
        {
            //saves the current category
            $cat->id_category_parent = core::get('id_category_parent');
            $cat->parent_deep        = core::get('deep');
            

            //saves the categories in the same parent the new orders
            $order = 0;
            foreach (core::get('brothers') as $id_cat) 
            {
                $id_cat = substr($id_cat,3);//removing the li_ to get the integer

                //not the main category so loading and saving
                if ($id_cat!=core::get('id_category'))
                {
                    $c = new Model_Category($id_cat);
                    $c->order = $order;
                    $c->save();
                }
                else
                {
                    //saves the main category
                    $cat->order  = $order;
                    $cat->save();
                }
                $order++;
            }

            //recalculating the deep of all the categories, we dont use tihs on eshop
            //$this->action_deep();

            $this->template->content = __('Saved');
        }
        else
            $this->template->content = __('Error');


    }



    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;

        $category = new Model_Category($this->request->param('id'));

        //update the elements related to that ad
        if ($category->loaded())
        {
            //update all the siblings this category has and set the category parent
            $query = DB::update('categories')
                        ->set(array('id_category_parent' => $category->id_category_parent))
                        ->where('id_category_parent','=',$category->id_category)
                        ->execute();

            //update all the products this category has and set the category parent
            $query = DB::update('products')
                        ->set(array('id_category' => $category->id_category_parent))
                        ->where('id_category','=',$category->id_category)
                        ->execute();

            try
            {
                $category->delete();
                $this->template->content = 'OK';
                Alert::set(Alert::SUCCESS, __('Category deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::ERROR, __('Category not deleted'));

        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'category','action'=>'index')));  

    }

    /**
     * Creates multiple categories just with name
     * @return void      
     */
    public function action_multy_categories()
    {
        $this->auto_render = FALSE;

        //update the elements related to that ad
        if ($_POST)
        {
            // d($_POST);
            if(core::post('multy_categories') !== "")
            {
                $multy_cats = explode(',', core::post('multy_categories'));
                $obj_category = new Model_Category();

                $insert = DB::insert('categories', array('name', 'seoname', 'id_category_parent'));
                foreach ($multy_cats as $name)
                {
                    $insert = $insert->values(array($name,$obj_category->gen_seoname($name),1));
                }
                // Insert everything with one query.
                $insert->execute();
            }
            else
                Alert::set(Alert::INFO, __('Select some categories first.'));
        }
        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'category','action'=>'index'))); 
    }

    /**
     * recalculating the deep of all the locations
     * @return [type] [description]
     */
    public function action_deep()
    {
        //getting all the cats as array
        $cats_arr       = Model_Category::get_as_array();

        $cats = new Model_Category();
        $cats = $cats->order_by('order','asc')->find_all()->cached()->as_array('id_category');
        foreach ($cats as $cat) 
        {
            $deep = 0;

            //getin the parent of this category
            $id_category_parent = $cats_arr[$cat->id_category]['id_category_parent'];

            //counting till we find the begining
            while ($id_category_parent != 1 AND $id_category_parent != 0) 
            {
                $id_category_parent = $cats_arr[$id_category_parent]['id_category_parent'];
                $deep++;
            }

            //saving the category only if different deep
            if ($cat->parent_deep != $deep)
            {
                $cat->parent_deep = $deep;
                $cat->save();
            }
        }

        //Alert::set(Alert::INFO, __('Success'));
        //HTTP::redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index'))); 
    }

	public function action_icon()
	{
		//get icon
		$icon = $_FILES['category_icon']; //file post
		
		$category = new Model_Category($this->request->param('id'));
        
        if ( 
            ! Upload::valid($icon) OR
            ! Upload::not_empty($icon) OR
            ! Upload::type($icon, explode(',',core::config('image.allowed_formats'))) OR
            ! Upload::size($icon, core::config('image.max_image_size').'M'))
        {
        	if ( Upload::not_empty($icon) && ! Upload::type($icon, explode(',',core::config('image.allowed_formats'))))
            {
                Alert::set(Alert::ALERT, $icon['name'].' '.sprintf(__('Is not valid format, please use one of this formats "%s"'),core::config('image.allowed_formats')));
				$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$category->id_category)));
            } 
            if( ! Upload::size($icon, core::config('image.max_image_size').'M'))
            {
                Alert::set(Alert::ALERT, $icon['name'].' '.sprintf(__('Is not of valid size. Size is limited to %s MB per image'),core::config('general.max_image_size')));
				$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$category->id_category)));
            }
            Alert::set(Alert::ALERT, $icon['name'].' '.__('Image is not valid. Please try again.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'category','action'=>'update')));
        }
        else
        {
            if($icon != NULL) // sanity check 
            {   
                // saving/uploading zip file to dir.
                $root = DOCROOT.'images/categories/'; //root folder
            	$icon_name = $category->seoname.'.png';
            	$width = core::config('image.width'); // @TODO dynamic !?
            	$height = core::config('image.height');// @TODO dynamic !?
            	$image_quality = core::config('image.quality');
                
                // if folder does not exist, try to make it
               	if ( ! file_exists($root) AND ! @mkdir($root, 0775, true)) { // mkdir not successful ?
                        Alert::set(Alert::ERROR, __('Image folder is missing and cannot be created with mkdir. Please correct to be able to upload images.'));
                        return; // exit function
                };

                // save file to root folder, file, name, dir
                if($file = Upload::save($icon, $icon_name, $root))
                {
	                // resize uploaded image 
	                Image::factory($file)
                        ->resize($width, $height, Image::AUTO)
                        ->save($root.$icon_name,$image_quality);

                }
                
                Alert::set(Alert::SUCCESS, $icon['name'].' '.__('Icon is uploaded.'));
				$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$category->id_category)));
            }
            
        }
	}   

}
