<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Update controllers 
 *
 * @package    OC
 * @category   Update
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Update extends Auth_Controller {    


    public function action_index()
    {
        
        //force update check reload
        if (Core::get('reload')==1 )
            Core::get_updates(TRUE);
        
        $versions = core::config('versions');

        if (Core::get('json')==1)
        {
            $this->auto_render = FALSE;
            $this->template = View::factory('js');
            $this->template->content = json_encode($versions);  
        }
        else
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Updates')));
            $this->template->title = __('Updates');
        
            //check if we have latest version of OC
            if (key($versions)!=core::version)
                Alert::set(Alert::ALERT,__('You are not using latest version of OC, please update.').
                    '<br/><br/><a class="btn btn-primary update_btn" href="'.Route::url('oc-panel',array('controller'=>'update','action'=>'latest')).'">
                '.__('Update').'</a>');
            

            //pass to view from local versions.php         
            $this->template->content = View::factory('oc-panel/pages/tools/versions',array('versions'       =>$versions,
                                                                                           'latest_version' =>key($versions)));
        }        

    }

    /**
     * This function will upgrade configs  
     */
    public function action_11()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'thanks_page',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'blog',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'blog_disqus',
                               'group_name'     =>'general', 
                               'config_value'   =>''));
        

        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
       
    }

    /**
     * This function will upgrade configs  
     */
    public function action_12()
    {
        //coupons product
        $prefix = Database::instance()->table_prefix();
        mysql_query("ALTER TABLE  `".$prefix."coupons` ADD  `id_product` INT NULL DEFAULT NULL AFTER  `id_coupon`");
        mysql_query("ALTER TABLE  `".$prefix."orders` ADD  `notes` VARCHAR( 245 ) NULL DEFAULT NULL");
        mysql_query("ALTER TABLE  `".$prefix."users` ADD  `signature` VARCHAR( 245 ) NULL DEFAULT NULL");
        mysql_query("ALTER TABLE  `".$prefix."visits` DROP  `contacted`");
        mysql_query("ALTER TABLE  `".$prefix."posts` ADD  `id_post_parent` INT NULL DEFAULT NULL AFTER  `id_user`");
        mysql_query("ALTER TABLE  `".$prefix."posts` ENGINE = MYISAM ");
        mysql_query("CREATE TABLE IF NOT EXISTS  `".$prefix."forums` (
                      `id_forum` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(145) NOT NULL,
                      `order` int(2) unsigned NOT NULL DEFAULT '0',
                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      `id_forum_parent` int(10) unsigned NOT NULL DEFAULT '0',
                      `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
                      `seoname` varchar(145) NOT NULL,
                      `description` varchar(255) NULL,
                      PRIMARY KEY (`id_forum`) USING BTREE,
                      UNIQUE KEY `".$prefix."forums_IK_seo_name` (`seoname`)
                    ) ENGINE=MyISAM");
        mysql_query("ALTER TABLE  `".$prefix."products` ADD `rate` FLOAT( 4, 2 ) NULL DEFAULT NULL ;");
        mysql_query("CREATE TABLE IF NOT EXISTS ".$prefix."reviews (
                    id_review int(10) unsigned NOT NULL AUTO_INCREMENT,
                    id_user int(10) unsigned NOT NULL,
                    id_order int(10) unsigned NOT NULL,
                    id_product int(10) unsigned NOT NULL,
                    rate int(2) unsigned NOT NULL DEFAULT '0',
                    description varchar(1000) NOT NULL,
                    created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    ip_address float DEFAULT NULL,
                    status tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (id_review) USING BTREE,
                    KEY ".$prefix."reviews_IK_id_user (id_user),
                    KEY ".$prefix."reviews_IK_id_order (id_order),
                    KEY ".$prefix."reviews_IK_id_product (id_product)
                    ) ENGINE=MyISAM;");

        // build array with new (missing) configs
        $configs = array(
                         array('config_key'     =>'minify',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'stripe_public',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'stripe_private',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'stripe_address',
                               'group_name'     =>'payment', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'alternative',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'related',
                               'group_name'     =>'product', 
                               'config_value'   =>'5'), 
                         array('config_key'     =>'faq',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'faq_disqus',
                               'group_name'     =>'general', 
                               'config_value'   =>''),
                         array('config_key'     =>'forums',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'reviews',
                               'group_name'     =>'product', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'demo_theme',
                               'group_name'     =>'product', 
                               'config_value'   =>'yeti'),
                        array('config_key'     =>'demo_resize',
                               'group_name'     =>'product', 
                               'config_value'   =>'1'), 
                        );
        

        $contents = array(array('order'=>'0',
                               'title'=>'[EMAIL.SENDER] wants to contact you!',
                               'seotitle'=>'contactadmin',
                               'description'=>"Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.BODY] \n\n Regards!",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'Ticket assigned to you: [TITLE]',
                               'seotitle'=>'assignagent',
                               'description'=>'[URL.QL]\n\n[DESCRIPTION]',
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'New review for [TITLE] [RATE]',
                               'seotitle'=>'reviewproduct',
                               'description'=>'[URL.QL]\n\n[RATE]\n\n[DESCRIPTION]',
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'New support ticket created `[TITLE]`',
                               'seotitle'=>'newticket',
                               'description'=>'We have received your support inquiry. We will try to answer you within the next 24 working hours, thank you for your patience.\n\n[URL.QL]',
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                        );
        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);
       
    }

    /**
     * This function will upgrade configs  
     */
    public function action_13()
    {
        //add new fields
        $prefix = Database::instance()->table_prefix();
        mysql_query("ALTER TABLE  `".$prefix."products` ADD  `updated` DATETIME NULL AFTER  `created`;");

        //updating emails
        $text =  '==== Order Details ====\nDate: [DATE]\nOrder ID: [ORDER.ID]\nName: [USER.NAME]\nEmail: [USER.EMAIL]\n\n==== Your Order ====\nProduct: [PRODUCT.TITLE]\nProduct Price: [PRODUCT.PRICE]\n\n[PRODUCT.NOTES][DOWNLOAD][EXPIRE][LICENSE]';
        DB::update('content')->set(array('description' => $text))->where('seotitle', '=', 'new.sale')->where('locale', '=', 'en_US')->execute();

        $text = '==== Update Details ====\nVersion: [VERSION]\nProduct name: [TITLE][DOWNLOAD][EXPIRE]\n\n==== Product Page ====\n[URL.PRODUCT]';
        DB::update('content')->set(array('description' => $text))->where('seotitle', '=', 'product.update')->where('locale', '=', 'en_US')->execute();


        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'download_hours',
                               'group_name'     =>'product', 
                               'config_value'   =>'72'), 
                         array('config_key'     =>'download_times',
                               'group_name'     =>'product', 
                               'config_value'   =>'3'),
                         array('config_key'     =>'sort_by',
                               'group_name'     =>'general', 
                               'config_value'   =>'1'));
        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

        //previous updates of DB
        $this->action_11();
        $this->action_12();

        //clean cache
        Cache::instance()->delete_all();
        Theme::delete_minified();
        
        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        Alert::set(Alert::SUCCESS, __('Software Updated to latest version!'));
        $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index'))); 
    }

    /**
     * This function will upgrade DB that didn't existed in verisons below 2.0.6
     */
    public function action_latest()
    {
        //activate maintenance mode
        Model_Config::set_value('general','maintenance',1);
        
        $versions = core::config('versions'); //loads OC software version array 
        $download_link = $versions[key($versions)]['download']; //get latest download link
        $version = key($versions); //get latest version

        $update_src_dir = DOCROOT.'update'; // update dir 
        $fname = $update_src_dir.'/'.$version.'.zip'; //full file name
        $folder_prefix = 'open-eshop-';
        $dest_dir = DOCROOT; //destination directory
        
        //check if exists file name
        if (file_exists($fname))  
            unlink($fname); 

        //create dir if doesnt exists
        if (!is_dir($update_src_dir))  
            mkdir($update_src_dir, 0775); 
        
        //verify we could get the zip file
        $file_content = core::curl_get_contents($download_link);
        if ($file_content == FALSE)
        {
            Alert::set(Alert::ALERT, __('We had a problem downloading latest version, try later please.'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //Write the file
        file_put_contents($fname, $file_content);

        //unpack zip
        $zip = new ZipArchive;
        // open zip file, and extract to dir
        if ($zip_open = $zip->open($fname)) 
        {
            $zip->extractTo($update_src_dir);
            $zip->close();  
        }   
        else 
        {
            Alert::set(Alert::ALERT, $fname.' '.__('Zip file faild to extract, please try again.'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //files to be replaced / move specific files
        $copy_list = array('oc/config/routes.php',
                          'oc/classes/',
                          'oc/modules/',
                          'oc/vendor/',
                          'oc/bootstrap.php',
                          'themes/',
                          'languages/',
                          'index.php',
                          'embed.js',
                          'README.md',);
      
        foreach ($copy_list as $dest_path) 
        { 
            $source = $update_src_dir.'/'.$folder_prefix.$version.'/'.$dest_path;
            $dest = $dest_dir.$dest_path;
            
            File::copy($source, $dest, TRUE);
        }
          
        //delete files when all finished
        File::delete($update_src_dir);

        //update themes, different request so doesnt time out
        $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'themes','id'=>str_replace('.', '', $version)))); 
        
    }

    /**
     * updates all themes to latest version from API license
     * @return void 
     */
    public function action_themes()
    {
        //activate maintenance mode
        Model_Config::set_value('general','maintenance',1);

        $licenses = array();

        //getting the licenses unique. to avoid downloading twice
        $themes = core::config('theme');
        foreach ($themes as $theme) 
        {
            $settings = json_decode($theme,TRUE);
            if (isset($settings['license']))
            {
                if (!in_array($settings['license'], $licenses))
                    $licenses[] = $settings['license'];
            }
        }

        //for each unique license then download!
        foreach ($licenses as $license) 
            Theme::download($license);   
        
        Alert::set(Alert::SUCCESS, __('Themes Updated'));

        //if theres version passed we redirect here to finish the update, if no version means was called directly
        if ( ($version = $this->request->param('id')) !==NULL)
            $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>$version)));   
        else
        {
            //deactivate maintenance mode
            Model_Config::set_value('general','maintenance',0);
            $this->request->redirect(Route::url('oc-panel', array('controller'=>'theme', 'action'=>'index'))); 
        }
            
        
        
    }
    
}