<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Product extends Auth_CrudAjax {

    /**
     * @var $_index_fields ORM fields shown in index
     */
    protected $_index_fields = array('title','status','price');


    protected $_search_fields = array('title','description');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'product';

    protected $_filter_fields = array(   
                                        'status' => array(0=>'Inactive',1=>'Active'),
                                        );

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('create','update');


	/**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_create()
    {
        //template header
        $this->template->title  = __('New Product');

        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
        $this->template->styles              = array('css/sortable.css' => 'screen',
        											 '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/css/jquery.fileupload.css'=>'screen',
                                                     'css/jasny-bootstrap.min.css'=>'screen',
                                                     );
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/jasny-bootstrap.min.js',
        											 'js/oc-panel/products.js',
        											 'js/jquery-sortable-min.js',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/vendor/jquery.ui.widget.js',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/jquery.iframe-transport.js',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/jquery.fileupload.js',
                                                     );											 
        											 

        $cats   = Model_Category::get_as_array();
        $order  = Model_Category::get_multidimensional();

        if(count($cats) <= 1)
        {
            Alert::set(Alert::WARNING, __('Please create a category first!'));
            $this->redirect(Route::url('oc-panel', array('controller'=>'category','action'=>'create')).'?rel=ajax');
        }

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
        	$obj_product->id_user      = $id_user;
        	$obj_product->seotitle     = $seotitle;
        	$obj_product->status       = (core::post('status')===NULL)?Model_Product::STATUS_NOACTIVE:Model_Product::STATUS_ACTIVE;
            $obj_product->updated      = Date::unix2mysql();
            $obj_product->offer_valid  = (core::post('offer_valid')!=NULL)? core::post('offer_valid').' 23:59:59' : NULL;
            $obj_product->featured     = (core::post('featured')!=NULL)? core::post('featured').' 23:59:59'  : NULL;

            if($file = $product['file_name'])
                $obj_product->file_name = $file;
        	
            // save product or throw exception
        	try 
        	{
        		$obj_product->save();
        		Alert::set(Alert::SUCCESS, __('Product is created.'));
        	} 
        	catch (Exception $e) 
        	{
        		throw HTTP_Exception::factory(500,$e->getMessage());
        	}

        	// save images
        	if(isset($_FILES))
        	{
            $obj_product->has_images = 0;
            
            foreach ($_FILES as $file_name => $file) 
            {  
                    if($file_name != 'file_name')
                        $file = $obj_product->save_image($file);
                        
                    if ($file)
                        $obj_product->has_images++;
            }
            
            //since theres images save the ad again...
            try 
            {
                $obj_product->save();
            } 
            catch (Exception $e) 
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        	}

        	$this->redirect(Route::url('oc-panel', array('controller'=>'product','action'=>'index')));  	
        }

    }


    public function action_update()
    {
        //template header
        $this->template->title  = __('Edit Product');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Product')));
        $this->template->styles              = array('css/sortable.css' => 'screen',
                                                     '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/css/jquery.fileupload.css'=>'screen',
                                                     'css/jasny-bootstrap.min.css'=>'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/jasny-bootstrap.min.js',
                                                     'js/oc-panel/products.js',
                                                     'js/jquery-sortable-min.js',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/vendor/jquery.ui.widget.js',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/jquery.iframe-transport.js',
                                                     '//cdn.jsdelivr.net/jquery.fileupload/9.5.2/js/jquery.fileupload.js',
                                                     );
                                                     
                                                     

        $cats   = Model_Category::get_as_array();
        $order  = Model_Category::get_multidimensional();

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
                if(is_numeric($deleted_image))
                {
                    $img_path = $obj_product->gen_img_path($obj_product->id_product, $obj_product->created);
                    $img_seoname = $obj_product->seotitle;

                    // delete image from Amazon S3
                    if (core::config('image.aws_s3_active'))
                    {
                        require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
                        $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
                        
                        //delete original image
                        $s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.$deleted_image.'.jpg');
                        //delete formated image
                        $s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.$deleted_image.'.jpg');
                        
                        //re-ordering image file names
                        for($i = $deleted_image; $i < $obj_product->has_images; $i++)
                        {
                            //rename original image
                            $s3->copyObject(core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.($i+1).'.jpg', core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.$i.'.jpg', S3::ACL_PUBLIC_READ);
                            $s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.($i+1).'.jpg');
                            //rename formated image
                            $s3->copyObject(core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.($i+1).'.jpg', core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.$i.'.jpg', S3::ACL_PUBLIC_READ);
                            $s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.($i+1).'.jpg');
                        }
                    }
                    
                    if (!is_dir($img_path)) 
                        return FALSE;
                    else
                    {   
                    
                        //delete original image
                        @unlink($img_path.$img_seoname.'_'.$deleted_image.'.jpg');
                        //delete formated image
                        @unlink($img_path.'thumb_'.$img_seoname.'_'.$deleted_image.'.jpg');
                        
                        //re-ordering image file names
                        for($i = $deleted_image; $i < $obj_product->has_images; $i++)
                        {
                            rename($img_path.$img_seoname.'_'.($i+1).'.jpg', $img_path.$img_seoname.'_'.$i.'.jpg');
                            rename($img_path.'thumb_'.$img_seoname.'_'.($i+1).'.jpg', $img_path.'thumb_'.$img_seoname.'_'.$i.'.jpg');
                        }
                        
                    }
                    
                    $obj_product->has_images = ($obj_product->has_images > 0) ? $obj_product->has_images-1 : 0;
                    $obj_product->updated = Date::unix2mysql();
                    try 
                    {
                        $obj_product->save();
                    } 
                    catch (Exception $e) 
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                    
                    $this->redirect(Route::url('oc-panel', array(   'controller'  =>'product',
                                                                    'action'      =>'update',
                                                                    'id'          =>$obj_product->id_product)));
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
                        @chmod($p_path, 0755);
                        //delete product
                        unlink($p_path);

                        $obj_product->file_name = '';
                        $obj_product->save();

                        $this->redirect(Route::url('oc-panel', array('controller'  =>'product',
                                                                              'action'      =>'update',
                                                                              'id'          =>$obj_product->id_product)));
                    }
                }
                
                $product['status']  = (!isset($_POST['status']) OR core::post('status')===NULL)?Model_Product::STATUS_NOACTIVE:Model_Product::STATUS_ACTIVE;
                $product['updated'] = Date::unix2mysql();
                //we do this so we assure use the entire day , nasty
                $product['offer_valid'] .= ' 23:59:59';
                $product['featured'] .= ' 23:59:59';

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

                    //notify users of new update
                    if($this->request->post('notify'))
                    {
                        //get users with that product
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
                            //download link
                            $download = '';
                            if ($obj_product->has_file()==TRUE)
                                $download = '\n\n==== '.__('Download').' ====\n'.Route::url('oc-panel', array('controller'  =>'profile','action'=>'orders'));
                            
                            //theres an expire? 0 = unlimited
                            $expire = '';
                            $expire_hours = Core::config('product.download_hours');
                            $expire_times = Core::config('product.download_times');
                            if ( ($expire_hours > 0 OR $expire_times > 0) AND $obj_product->has_file()==TRUE)
                            {
                                if ($expire_hours > 0 AND $expire_times > 0)
                                    $expire = sprintf(__('Your download expires in %u hours and can be downloaded %u times.'),$expire_hours,$expire_times);
                                elseif ($expire_hours > 0)
                                    $expire = sprintf(__('Your download expires in %u hours.'),$expire_hours);
                                elseif ( $expire_times > 0)
                                    $expire = sprintf(__('Can be downloaded %u times.'),$expire_times);

                                $expire = '\n'.$expire;
                            }


                            if ( ! Email::content($users, '', NULL, NULL, 'product-update', 
                                                        array('[TITLE]'         => $obj_product->title,
                                                              '[URL.PRODUCT]'   => Route::url('product', array('seotitle'=>$obj_product->seotitle,'category'=>$obj_product->category->seoname)),
                                                              '[DOWNLOAD]'      => $download,
                                                              '[EXPIRE]'        => $expire,
                                                              '[VERSION]'       => $obj_product->version)))
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
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                // save images
                if(isset($_FILES))
                {
                    foreach ($_FILES as $file_name => $file) 
                    {  
                        if($file_name != 'file_name')
                            $file = $obj_product->save_image($file);
                            
                        if ($file)
                            $obj_product->has_images++;
                    }
                    
                    //since theres images save the ad again...
                    try 
                    {
                        $obj_product->save();
                    } 
                    catch (Exception $e) 
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }
            }
        }
    }

    public function action_upload()
    {   

        try 
        {
            if($file = $_FILES['fileupload'])
            {
                $obj_product = new Model_Product();
                $file = $obj_product->save_product($file);
                if($file !== FALSE)
                    echo $file;
                // else 
                    // echo false; 
            }
            exit();
        } 
        catch (Exception $e) 
        {
            // echo 'error';
            exit();
        }
        
    }
    public function action_delete_file()
    {
        //delete product file
        
            $file_name = $_POST['file_name'];
            $obj_product = new Model_Product();
            $p_path = $obj_product->get_file($file_name); 
            
            if (!is_file($p_path)) 
            {
                echo $p_path;
                // echo $p_path;
                exit();
            }
            else
            {
                chmod($p_path, 0775);
                //delete product
                unlink($p_path);
            }        
        // $product_delete = core::post('product_delete');
        // if($product_delete)
        // {
        //     $p_path = $obj_product->get_file($obj_product->file_name);
        //     if (!is_file($p_path)) 
        //     {
        //         return FALSE;
        //     }
        //     else
        //     {   
        //         chmod($p_path, 0775);
        //         //delete product
        //         unlink($p_path);

        //         $obj_product->file_name = '';
        //         $obj_product->save();

        //         $this->redirect(Route::url('oc-panel', array('controller'  =>'product',
        //                                                               'action'      =>'update',
        //                                                               'id'          =>$obj_product->id_product)));
        //     }
        // }
    }

}
