<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Update controllers 
 *
 * @package    OC
 * @category   Update
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
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
     * This function will upgrate configs  
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
     * This function will upgrate configs  
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
                        );
        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

        $this->action_11();

        //clean cache
        Cache::instance()->delete_all();
        Theme::delete_minified();
            
        Alert::set(Alert::SUCCESS, __('Updated'));
        $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index'))); 
    }

    /**
     * This function will upgrate DB that didn't existed in verisons below 2.0.6
     * changes added: config for custom field
     */
    public function action_latest()
    {
        
        $versions = core::config('versions'); //loads OC software version array 
        $download_link = $versions[key($versions)]['download']; //get latest download link
        $version = key($versions); //get latest version

    //@todo do a walidation of downloaded file and if its downloaded, trow error if something is worong
    // review all to be automatic

        $update_src_dir = DOCROOT."update"; // update dir 
        $fname = $update_src_dir."/".$version.".zip"; //full file name
        $folder_prefix = 'open-eshop-';
        $dest_dir = DOCROOT; //destination directory
        
        //check if exists file name
        if (file_exists($fname))  
            unlink($fname); 

        //create dir if doesnt exists
        if (!is_dir($update_src_dir))  
            mkdir($update_src_dir, 0775); 
          
        //download file
        $download = file_put_contents($fname, fopen($download_link, 'r'));

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
          
        //delete file when all finished
        File::delete($update_src_dir);
        $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>str_replace('.', '', $version))));
    }

    
}