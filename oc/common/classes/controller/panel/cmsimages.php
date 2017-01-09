<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Cmsimages extends Auth_Controller {

    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {        
        $path        = 'images/cms/';
        $root        = DOCROOT.$path;
        $list_images = Kohana::list_files('../'.$path);
        $images      = array();

        $i = 0;

        foreach ($list_images as $key => $image)
        {
            $image_name       = str_replace($root, '', $image);
            $image_path       = $root.$image_name;
            $image_url        = Core::config('general.base_url').$path.$image_name ;

            $images[$i]['name'] = $image_name;
            $images[$i]['path'] = $image_path;
            $images[$i]['url']  = $image_url;

            $i++;
        }
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('CMS Images')));

        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        $this->template->title               = __('CMS Images');
        $this->template->content             = View::factory('oc-panel/pages/cmsimages/index', array('images' => $images));
    }

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');
        
        if ( ! isset($_FILES['image']))
        {
            $this->template->content = json_encode('KO');
            return;
        }

        $image = $_FILES['image'];
                
        if (core::config('image.aws_s3_active'))
        {
            require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
            $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
        }
        
        if ( 
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, explode(',',core::config('image.allowed_formats'))) OR
            ! Upload::size($image, core::config('image.max_image_size').'M'))
        {
            if ( Upload::not_empty($image) AND ! Upload::type($image, explode(',',core::config('image.allowed_formats'))))
            {
                $this->template->content = json_encode(array('msg' => $image['name'].' '.sprintf(__('Is not valid format, please use one of this formats "%s"'), core::config('image.allowed_formats'))));
                return;
            } 
            if ( ! Upload::size($image, core::config('image.max_image_size').'M'))
            {
                $this->template->content = json_encode(array('msg' => $image['name'].' '.sprintf(__('Is not of valid size. Size is limited to %s MB per image'), core::config('image.max_image_size'))));
                return;
            }

            $this->template->content = json_encode(array('msg' => $image['name'].' '.__('Image is not valid. Please try again.')));
            return;
        }
        elseif ($image != NULL) // sanity check 
        {   
            // saving/uploading img file to dir.
            $path = 'images/cms/';
            $root = DOCROOT.$path; //root folder
            $image_name = URL::title(pathinfo($image['name'], PATHINFO_FILENAME));
            $image_name = Text::limit_chars(URL::title(pathinfo($image['name'], PATHINFO_FILENAME)), 200);
            $image_name = time().'.'.$image_name;
                
            // if folder does not exist, try to make it
            if ( ! file_exists($root) AND ! @mkdir($root, 0775, true)) { // mkdir not successful ?
                $this->template->content = json_encode(array('msg' => __('Image folder is missing and cannot be created with mkdir. Please correct to be able to upload images.')));  
                return; // exit function
            };
                
            // save file to root folder, file, name, dir
            if ($file = Upload::save($image, $image_name, $root))
            {
                // put image to Amazon S3
                if (core::config('image.aws_s3_active'))
                    $s3->putObject($s3->inputFile($file), core::config('image.aws_s3_bucket'), $path.$image_name, S3::ACL_PUBLIC_READ);
                                        
                $this->template->content = json_encode(array('link' => Core::config('general.base_url').$path.$image_name));
                return;
            }
            else
            {
                $this->template->content = json_encode(array('msg' => $image['name'].' '.__('Image file could not been saved.')));
                return;
            }
                    
            $this->template->content = json_encode(array('msg' => $image['name'].' '.__('Image is not valid. Please try again.')));           
        }
    }

    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        if (! Core::get('name'))
            return FALSE;

        if (core::config('image.aws_s3_active'))
        {
            require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
            $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
        }

        $image_name = Core::get('name');
        $root       = DOCROOT.'images/cms/'; //root folder
            
        if (!is_dir($root)) 
        {
            return FALSE;
        }
        else
        {   
            //delete image
            @unlink($root.$image_name);
            
            // delete image from Amazon S3
            if(core::config('image.aws_s3_active'))
                $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/cms/'.$image_name);
        }

        return TRUE;
    }

}
