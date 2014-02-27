<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Product extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('title','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'product';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('create','update');

    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        parent::action_index('oc-panel/pages/products/index');
    }    

	/**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_create()
    {
        //template header
        $this->template->title  = __('New Product');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Product')));
        $this->template->styles              = array('css/sortable.css' => 'screen',
        											 'http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
        											 'js/oc-panel/products.js',
        											 'js/jquery-sortable-min.js');
        											 
        											 

        list($cats,$order)  = Model_Category::get_all();
        $obj_product = new Model_Product();

        // get currencies from product, returns array
        $currency = $obj_product::get_currency(); 

        $this->template->content = View::factory('oc-panel/pages/products/create',array('categories'		=>$cats,
        																				'order_categories' 	=>$order,
        																				'currency'			=>$currency));

        if($product = $this->request->post())
        {
        	$id_user = Auth::instance()->get_user()->id_user;
        	
        	// set custom values from POST
        	foreach ($product as $field => $value) 
        	{   
        		if($field != 'submit')
        			$obj_product->$field = $value;
        	}
        	$seotitle = $obj_product->gen_seotitle($product['title']);
        	
            // set non POST values
        	$obj_product->id_user = $id_user;
        	$obj_product->seotitle = $seotitle;
        	$obj_product->status = (core::post('status')===NULL)?Model_Product::STATUS_NOACTIVE:Model_Product::STATUS_ACTIVE;
            $obj_product->updated = Date::unix2mysql();

            // save product file
            if($file = $_FILES['file_name'] AND $_FILES['file_name']['size'] != 0)
            {
                $file = $obj_product->save_product($file);
                if($file != FALSE)
                    $obj_product->file_name = $file;
                else
                    Alert::set(Alert::WARNING, __('Product is not uploaded.'));
            }
        	
            // save product or trow exeption
        	try 
        	{
        		$obj_product->save();
        		Alert::set(Alert::SUCCESS, __('Product is created.'));
                Sitemap::generate();
        	} 
        	catch (Exception $e) 
        	{
        		throw new HTTP_Exception_500($e->getMessage());
        	}

        	// save images
        	if(isset($_FILES))
        	{
        		foreach ($_FILES as $file_name => $file) 
        		{  
                    if($file_name != 'file_name')
        			    echo $file = $obj_product->save_image($file);
        		}
        	}

        	$this->request->redirect(Route::url('oc-panel', array('controller'=>'product','action'=>'index')));  	
        }

    }


    public function action_update()
    {
        //template header
        $this->template->title  = __('Edit Product');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Product')));
        $this->template->styles              = array('css/sortable.css' => 'screen',
                                                     'http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer']   = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                     'js/oc-panel/products.js',
                                                     'js/jquery-sortable-min.js');
                                                     
                                                     

        list($cats,$order)  = Model_Category::get_all();

        $obj_product = new Model_Product($this->request->param('id'));

        if($obj_product->loaded())
        {
            // get currencies from product, returns array
            $currency = $obj_product::get_currency(); 

            $this->template->content = View::factory('oc-panel/pages/products/update',array('product'           =>$obj_product,
                                                                                            'categories'        =>$cats,
                                                                                            'order_categories'  =>$order,
                                                                                            'currency'          =>$currency));
            
            if($product = $this->request->post())
            {
                // save product file
                if(isset($_FILES['file_name']))
                {    
                    if($file = $_FILES['file_name'])
                    {
                        $file = $obj_product->save_product($file);
                        if($file != FALSE)
                            $obj_product->file_name = $file;
                        else
                            Alert::set(Alert::INFO, __('Product is not uploaded.'));
                    }
                }

                // deleting single image by path 
                $deleted_image = core::post('img_delete');
                if($deleted_image)
                {
                    $img_path = $obj_product->gen_img_path($obj_product->id_product, $obj_product->created);
                    
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

                        $this->request->redirect(Route::url('oc-panel', array('controller'  =>'product',
                                                                              'action'      =>'update',
                                                                              'id'          =>$obj_product->id_product)));
                    }
                }// end of img delete

                //delete product file
                $product_delete = core::post('product_delete');
                if($product_delete)
                {
                    $p_path = $obj_product->get_file($obj_product->file_name);
                    if (!is_file($p_path)) 
                    {
                        return FALSE;
                    }
                    else
                    {   
                        chmod($p_path, 0775);
                        //delete product
                        unlink($p_path);

                        $obj_product->file_name = '';
                        $obj_product->save();

                        $this->request->redirect(Route::url('oc-panel', array('controller'  =>'product',
                                                                              'action'      =>'update',
                                                                              'id'          =>$obj_product->id_product)));
                    }
                }
                $product['status']  = (core::post('status')===NULL)?Model_Product::STATUS_NOACTIVE:Model_Product::STATUS_ACTIVE;
                $product['updated'] = Date::unix2mysql();
                // each field in edit product
                foreach ($product as $field => $value) 
                {
                    // do not include submit
                    if($field != 'submit' AND $field != 'notify')
                    {
                        // check if its different, and set it is
                        if($value != $obj_product->$field)
                        {
                            $obj_product->$field = $value;
                            // if title is changed, make new seotitle
                            if($field == 'title')
                            {
                                $seotitle = $obj_product->gen_seotitle($product['title']);
                                $obj_product->seotitle = $seotitle;
                            }
                        }
                    }
                }
                // save product or trow exeption
                try 
                {
                    $obj_product->save();
                    Alert::set(Alert::SUCCESS, __('Product saved.'));
                    Sitemap::generate();
                    if($this->request->post('notify'))
                    {

                        $query = DB::select('email')->select('name')
                            ->from(array('users', 'u'))
                            ->join(array('orders', 'o'),'INNER')
                            ->on('u.id_user','=','o.id_user')
                            ->where('u.status','=',Model_User::STATUS_ACTIVE)
                            ->where('o.status', '=', Model_Order::STATUS_PAID)
                            ->where('o.id_product', '=', $obj_product->id_product)
                            ->execute();
                            
                        $users = $query->as_array();
                        if (count($users)>0)
                        { 
                            if ( !Email::content($users, '', NULL, NULL, 'product.update', 
                                                        array('[TITLE]'=>$obj_product->title,
                                                              '[URL.PRODUCT]'=> Route::url('product', array('seotitle'=>$obj_product->seotitle,'category'=>$obj_product->category->seoname)),
                                                              '[URL.PURCHASES]'=>Route::url('oc-panel', array('controller'  =>'profile','action'=>'orders')),
                                                              '[VERSION]'=>$obj_product->version)))
                                Alert::set(Alert::ERROR,__('Error on mail delivery, not sent'));
                            else 
                                Alert::set(Alert::SUCCESS,__('Email sent to all the users'));
                        }
                        else
                        {
                            Alert::set(Alert::ERROR,__('Mail not sent'));
                        }
                    }

                } 
                catch (Exception $e) 
                {
                    throw new HTTP_Exception_500($e->getMessage());
                }

                // save images
                if(isset($_FILES))
                {
                    foreach ($_FILES as $file_name => $file) 
                    {  
                        if($file_name != 'file_name')
                            echo $file = $obj_product->save_image($file);
                    }
                }
            }
        }
    }

}
