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
class Controller_Panel_Update extends Controller_Panel_OC_Update {    

    /**
     * This function will upgrade configs  
     */
    public function action_16()
    {
        //subscriber field
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `subscriber` tinyint(1) NOT NULL DEFAULT '1'")->execute();
        }catch (exception $e) {}

        //updating contents replacing . for _
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle=REPLACE(seotitle,'.','-') WHERE type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='affiliate-commission' WHERE seotitle='affiliatecommission' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='new-ticket' WHERE seotitle='newticket' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='review-product' WHERE seotitle='reviewproduct' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='assign-agent' WHERE seotitle='assignagent' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='contact-admin' WHERE seotitle='contactadmin' AND type='email'")->execute();
        }catch (exception $e) {}
        //end updating emails


        //ip_address from float to bigint
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` CHANGE last_ip last_ip BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."visits` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."licenses` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."downloads` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."tickets` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."reviews` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."affiliates` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}

        //crontab table
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."crontab` (
                    `id_crontab` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(50) NOT NULL,
                      `period` varchar(50) NOT NULL,
                      `callback` varchar(140) NOT NULL,
                      `params` varchar(255) DEFAULT NULL,
                      `description` varchar(255) DEFAULT NULL,
                      `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `date_started` datetime  DEFAULT NULL,
                      `date_finished` datetime  DEFAULT NULL,
                      `date_next` datetime  DEFAULT NULL,
                      `times_executed`  bigint DEFAULT '0',
                      `output` varchar(50) DEFAULT NULL,
                      `running` tinyint(1) NOT NULL DEFAULT '0',
                      `active` tinyint(1) NOT NULL DEFAULT '1',
                      PRIMARY KEY (`id_crontab`),
                      UNIQUE KEY `".self::$db_prefix."crontab_UK_name` (`name`)
                  ) ENGINE=MyISAM;")->execute();
        }catch (exception $e) {}

        //crontabs
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Sitemap', '* 3 * * *', 'Sitemap::generate', NULL, 'Regenerates the sitemap everyday at 3am',1),
                                    ('Clean Cache', '* 5 * * *', 'Core::delete_cache', NULL, 'Once day force to flush all the cache.', 1),
                                    ('Optimize DB', '* 4 1 * *', 'Core::optimize_db', NULL, 'once a month we optimize the DB', 1);")->execute();
        }catch (exception $e) {}
        
        //delete old sitemap config
        try
        {
            DB::query(Database::DELETE,"DELETE FROM ".self::$db_prefix."config WHERE (config_key='expires' OR config_key='on_post') AND  group_name='sitemap'")->execute();
        }catch (exception $e) {}

        //categories description to HTML
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` CHANGE  `description`  `description` TEXT NULL DEFAULT NULL;")->execute();
        }catch (exception $e) {}
        
        $categories = new Model_Category();
        $categories = $categories->find_all();
        foreach ($categories as $category) 
        {
            $category->description = Text::bb2html($category->description,TRUE);
            try {
                $category->save();
            } catch (Exception $e) {}
        }

        //content description to HTML
        $contents = new Model_Content();
        $contents = $contents->find_all();
        foreach ($contents as $content) 
        {
            $content->description = Text::bb2html($content->description,TRUE);
            try {
                $content->save();
            } catch (Exception $e) {}
        }

        //blog description to HTML
        $posts =  new Model_Post();
		$posts = $posts->where('id_forum','IS',NULL)->find_all();
        foreach ($posts as $post) 
        {
            $post->description = Text::bb2html($post->description,TRUE);
            try {
                $post->save();
            } catch (Exception $e) {}
        }

        //User description About
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users`  ADD  `description` TEXT NULL DEFAUlT NULL AFTER  `password` ")->execute();
        }catch (exception $e) {}
        
        $configs = array( 
                         array('config_key'     =>'banned_words_replacement',
                               'group_name'     =>'general', 
                               'config_value'   =>'xxx'), 
                         array('config_key'     =>'banned_words',
                               'group_name'     =>'general', 
                               'config_value'   =>''), 
                         array('config_key'     =>'authorize_sandbox',
                               'group_name'     =>'payment', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'authorize_login',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'authorize_key',
                               'group_name'     =>'payment', 
                               'config_value'   =>''),
                         array('config_key'     =>'elastic_active',
                               'group_name'     =>'email', 
                               'config_value'   =>0),
                         array('config_key'     =>'elastic_username',
                               'group_name'     =>'email', 
                               'config_value'   =>''),
                         array('config_key'     =>'elastic_password',
                               'group_name'     =>'email', 
                               'config_value'   =>''),
                         array('config_key'     =>'disallowbots',
                               'group_name'     =>'general', 
                               'config_value'   => 0),
                         array('config_key'     =>'use_cdn',
                               'group_name'     =>'general', 
                               'config_value'   => '1'),

                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs); 
		        
    }

    /**
     * This function will upgrade configs  
     */
    public function action_15()
    {
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".self::$db_prefix."config DROP INDEX ".self::$db_prefix."config_IK_group_name_AND_config_key")->execute();
        }catch (exception $e) {}
        
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".self::$db_prefix."config ADD PRIMARY KEY (config_key)")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".self::$db_prefix."config_UK_group_name_AND_config_key ON ".self::$db_prefix."config(`group_name` ,`config_key`)")->execute();
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
    public function action_14()
    {
        //affiliates
        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".self::$db_prefix."affiliates (
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
                    KEY ".self::$db_prefix."affiliates_IK_id_user (id_user),
                    KEY ".self::$db_prefix."affiliates_IK_id_order (id_order),
                    KEY ".self::$db_prefix."affiliates_IK_id_product (id_product)
                    ) ENGINE=MyISAM;")->execute();
        
        //product affiliate_percentage
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."products` ADD  affiliate_percentage decimal(14,3) NOT NULL DEFAULT '0' AFTER  `rate`;")->execute();
        } catch (exception $e) {}

        //paypal for user
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `paypal_email` varchar(145) DEFAULT NULL AFTER  `email`;")->execute();
        } catch (exception $e) {}
       
        //visits id affiliate
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."visits` ADD  `id_affiliate` int(10) unsigned DEFAULT NULL AFTER  `id_user`;")->execute();
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
    public function action_13()
    {
        //add new fields
        
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."products` ADD  `updated` DATETIME NULL AFTER  `created`;")->execute();
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
    public function action_12()
    {
        //coupons product

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."coupons` ADD  `id_product` INT NULL DEFAULT NULL AFTER  `id_coupon`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD  `notes` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `signature` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."visits` DROP  `contacted`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `id_post_parent` INT NULL DEFAULT NULL AFTER  `id_user`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ENGINE = MYISAM ")->execute();
        }catch (exception $e) {}
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."products` ADD `rate` FLOAT( 4, 2 ) NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".self::$db_prefix."forums` (
                      `id_forum` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(145) NOT NULL,
                      `order` int(2) unsigned NOT NULL DEFAULT '0',
                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      `id_forum_parent` int(10) unsigned NOT NULL DEFAULT '0',
                      `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
                      `seoname` varchar(145) NOT NULL,
                      `description` varchar(255) NULL,
                      PRIMARY KEY (`id_forum`) USING BTREE,
                      UNIQUE KEY `".self::$db_prefix."forums_IK_seo_name` (`seoname`)
                    ) ENGINE=MyISAM")->execute();
        
        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".self::$db_prefix."reviews (
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
                    KEY ".self::$db_prefix."reviews_IK_id_user (id_user),
                    KEY ".self::$db_prefix."reviews_IK_id_order (id_order),
                    KEY ".self::$db_prefix."reviews_IK_id_product (id_product)
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

    
}