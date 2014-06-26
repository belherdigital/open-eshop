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
            $this->template->title = __('Updates');
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

            //version numbers in a key value
            $version_nums = array();
            foreach ($versions as $version=>$values)
                $version_nums[] = $version;

            $latest_version = current($version_nums);
            $latest_version_update = next($version_nums);


            //check if we have latest version of OC and using the previous version then we allow to auto update
            if ($latest_version!=core::VERSION AND core::VERSION == $latest_version_update )
                Alert::set(Alert::ALERT,__('You are not using latest version, please update.').
                    '<br/><br/><a class="btn btn-primary update_btn" href="'.Route::url('oc-panel',array('controller'=>'update','action'=>'latest')).'">
                '.__('Update').'</a>');
            elseif ($latest_version!=core::VERSION AND core::VERSION != $latest_version_update )
                Alert::set(Alert::ALERT,__('You are using an old version, can not update automatically, please update manually.'));

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

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."coupons` ADD  `id_product` INT NULL DEFAULT NULL AFTER  `id_coupon`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."orders` ADD  `notes` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."users` ADD  `signature` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."visits` DROP  `contacted`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."posts` ADD  `id_post_parent` INT NULL DEFAULT NULL AFTER  `id_user`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."posts` ENGINE = MYISAM ")->execute();
        }catch (exception $e) {}
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."products` ADD `rate` FLOAT( 4, 2 ) NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".$prefix."forums` (
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
                    ) ENGINE=MyISAM")->execute();
        
        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".$prefix."reviews (
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
                    ) ENGINE=MyISAM;")->execute();

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
                               'seotitle'=>'contact-admin',
                               'description'=>"Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.BODY] \n\n Regards!",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'Ticket assigned to you: [TITLE]',
                               'seotitle'=>'assign-agent',
                               'description'=>'[URL.QL]\n\n[DESCRIPTION]',
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'New review for [TITLE] [RATE]',
                               'seotitle'=>'review-product',
                               'description'=>'[URL.QL]\n\n[RATE]\n\n[DESCRIPTION]',
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'New support ticket created `[TITLE]`',
                               'seotitle'=>'new-ticket',
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
        
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."products` ADD  `updated` DATETIME NULL AFTER  `created`;")->execute();
        } catch (exception $e) {}
        

        //updating emails
        $text =  '==== Order Details ====\nDate: [DATE]\nOrder ID: [ORDER.ID]\nName: [USER.NAME]\nEmail: [USER.EMAIL]\n\n==== Your Order ====\nProduct: [PRODUCT.TITLE]\nProduct Price: [PRODUCT.PRICE]\n\n[PRODUCT.NOTES][DOWNLOAD][EXPIRE][LICENSE]';
        DB::update('content')->set(array('description' => $text))->where('seotitle', '=', 'new-sale')->where('locale', '=', 'en_US')->execute();

        $text = '==== Update Details ====\nVersion: [VERSION]\nProduct name: [TITLE][DOWNLOAD][EXPIRE]\n\n==== Product Page ====\n[URL.PRODUCT]';
        DB::update('content')->set(array('description' => $text))->where('seotitle', '=', 'product-update')->where('locale', '=', 'en_US')->execute();


        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'download_hours',
                               'group_name'     =>'product', 
                               'config_value'   =>'72'), 
                         array('config_key'     =>'download_times',
                               'group_name'     =>'product', 
                               'config_value'   =>'3'),
                         array('config_key'     =>'sort_by',
                               'group_name'     =>'general', 
                               'config_value'   =>'published-asc'),
                         array('config_key'     =>'number_of_orders',
                               'group_name'     =>'product', 
                               'config_value'   =>'0'));
        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

    }

    /**
     * This function will upgrade configs  
     */
    public function action_14()
    {

        $prefix = Database::instance()->table_prefix();

        //affiliates
        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".$prefix."affiliates (
                    id_affiliate int(10) unsigned NOT NULL AUTO_INCREMENT,
                    id_user int(10) unsigned NOT NULL,
                    id_order int(10) unsigned NOT NULL,
                    id_order_payment int(10) unsigned,
                    id_product int(10) unsigned NOT NULL,
                    percentage decimal(14,3) NOT NULL DEFAULT '0',
                    amount decimal(14,3) NOT NULL DEFAULT '0',
                    currency char(3) NOT NULL,
                    created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    date_to_pay datetime DEFAULT NULL,
                    date_paid datetime DEFAULT NULL,
                    ip_address float DEFAULT NULL,
                    status tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (id_affiliate) USING BTREE,
                    KEY ".$prefix."affiliates_IK_id_user (id_user),
                    KEY ".$prefix."affiliates_IK_id_order (id_order),
                    KEY ".$prefix."affiliates_IK_id_product (id_product)
                    ) ENGINE=MyISAM;")->execute();
        
        //product affiliate_percentage
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."products` ADD  affiliate_percentage decimal(14,3) NOT NULL DEFAULT '0' AFTER  `rate`;")->execute();
        } catch (exception $e) {}

        //paypal for user
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."users` ADD  `paypal_email` varchar(145) DEFAULT NULL AFTER  `email`;")->execute();
        } catch (exception $e) {}
       
        //visits id affiliate
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."visits` ADD  `id_affiliate` int(10) unsigned DEFAULT NULL AFTER  `id_user`;")->execute();
        } catch (exception $e) {}

        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'qr_code',
                               'group_name'     =>'product', 
                               'config_value'   =>'0'), 
                        array('config_key'     =>'bitpay_apikey',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                        array('config_key'     =>'active',
                               'group_name'     =>'affiliate', 
                               'config_value'   =>'0'), 
                        array('config_key'     =>'cookie',
                               'group_name'     =>'affiliate', 
                               'config_value'   =>'90'), 
                        array('config_key'     =>'payment_days',
                               'group_name'     =>'affiliate', 
                               'config_value'   =>'30'), 
                        array('config_key'     =>'payment_min',
                               'group_name'     =>'affiliate', 
                               'config_value'   =>'50'),
                        array('config_key'     =>'tos',
                               'group_name'     =>'affiliate', 
                               'config_value'   =>''), 
                         );
        
         $contents = array(array('order'=>'0',
                               'title'=>'Congratulations! New affiliate commission [AMOUNT]',
                               'seotitle'=>'affiliate-commission',
                               'description'=>"Congratulations!,\n\n We just registered a sale from your affiliate link for the amount of [AMOUNT], check them all at your affiliate panel [URL.AFF]. \n\n Thanks for using our affiliate program!",
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
    public function action_15()
    {


        $prefix = Database::instance()->table_prefix();

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".$prefix."config DROP INDEX ".$prefix."config_IK_group_name_AND_config_key")->execute();
        }catch (exception $e) {}
        
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".$prefix."config ADD PRIMARY KEY (config_key)")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".$prefix."config_UK_group_name_AND_config_key ON ".$prefix."config(`group_name` ,`config_key`)")->execute();
        }catch (exception $e) {}
             
        //set sitemap to 0
        Model_Config::set_value('sitemap','on_post',0);     

        $configs = array( 
                         array('config_key'     =>'ocacu',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

    }


    /**
     * This function will upgrade configs  
     */
    public function action_16()
    {
        //previous updates of DB
        $this->action_11();
        $this->action_12();
        $this->action_13();
        $this->action_14();
        $this->action_15();

        $prefix = Database::instance()->table_prefix();

        //subscriber field
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."users` ADD `subscriber` tinyint(1) NOT NULL DEFAULT '1'")->execute();
        }catch (exception $e) {}

        //updating contents replacing . for _
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle=REPLACE(seotitle,'.','-') WHERE type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle='affiliate-commission' WHERE seotitle='affiliatecommission' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle='new-ticket' WHERE seotitle='newticket' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle='review-product' WHERE seotitle='reviewproduct' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle='assign-agent' WHERE seotitle='assignagent' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle='contact-admin' WHERE seotitle='contactadmin' AND type='email'")->execute();
        }catch (exception $e) {}
        //end updating emails


        
        $configs = array( 
                         array('config_key'     =>'banned_words_replacement',
                               'group_name'     =>'general', 
                               'config_value'   =>'xxx'), 
                         array('config_key'     =>'banned_words',
                               'group_name'     =>'general', 
                               'config_value'   =>''), 
                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);


        //clean cache
        Cache::instance()->delete_all();
        Theme::delete_minified();
        
        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        Alert::set(Alert::SUCCESS, __('Software Updated to latest version!'));
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index'))); 
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
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
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
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //files to be replaced / move specific files
        $copy_list = array('oc/config/routes.php',
                          'oc/classes/',
                          'oc/modules/',
                          'oc/vendor/',
                          'oc/bootstrap.php',
                          'oc/kohana/',
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

        //clean cache
        Cache::instance()->delete_all();
        Theme::delete_minified();

        //update themes, different request so doesnt time out
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'themes','id'=>str_replace('.', '', $version)))); 
        
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
            $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>$version)));   
        else
        {
            //deactivate maintenance mode
            Model_Config::set_value('general','maintenance',0);
            $this->redirect(Route::url('oc-panel', array('controller'=>'theme', 'action'=>'index'))); 
        }
            
        
        
    }
    
}