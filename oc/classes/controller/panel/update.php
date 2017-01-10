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
     * This function will upgrade DB that didn't existed in versions prior to 2.7.0
     */
    public function action_270()
    {

        //new configs
        $configs = array(
                        
                        array( 'config_key'     => 'elastic_listname',
                               'group_name'     => 'email', 
                               'config_value'   => ''),
                        array( 'config_key'     => 'private_site',
                               'group_name'     => 'general', 
                               'config_value'   => '0'),
                        array( 'config_key'     => 'private_site_page',
                               'group_name'     => 'general', 
                               'config_value'   => ''),
                        );
        
        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.6.0
     */
    public function action_260()
    {

        //new configs
        $configs = array(
                        
                        array( 'config_key'     => 'stripe_3d_secure',
                               'group_name'     => 'payment', 
                               'config_value'   => '0'),
                        );

        //get theme license and add it to the config
        if (Theme::get('license')!==NULL)
        {            
            $configs[]= array( 'config_key'     => 'date',
                               'group_name'     => 'license', 
                               'config_value'   => Theme::get('license_date')
                               );

            $configs[]= array( 'config_key'     => 'number',
                               'group_name'     => 'license', 
                               'config_value'   => Theme::get('license')
                               );
        }
        
        
        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.5.0
     */
    public function action_250()
    {
        //fixes yahoo login
        try 
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."config` SET `config_value`= REPLACE(`config_value`,',\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"id\":',',\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"key\":') WHERE `group_name` = 'social' AND `config_key`='config' AND `config_value` LIKE '%,\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"id\":%'")->execute();
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET description='Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.SUBJECT]\n\n [EMAIL.BODY] \n\n Regards!' WHERE seotitle='contact-admin'")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(
                        array( 'config_key'     => 'notify_name',
                               'group_name'     => 'email', 
                               'config_value'   => 'no-reply '.core::config('general.site_name')),
                        array( 'config_key'     => 'mercadopago_client_id',
                               'group_name'     => 'payment', 
                               'config_value'   => ''),
                        array( 'config_key'     => 'mercadopago_client_secret',
                               'group_name'     => 'payment', 
                               'config_value'   => ''),
                        );

        Model_Config::config_array($configs);
    }

     /**
     * This function will upgrade DB that didn't existed in versions prior to 2.4.0
     */
    public function action_240()
    {
     //google 2 step auth
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `google_authenticator` varchar(40) DEFAULT NULL")->execute();
        }catch (exception $e) {}


        //new configs
        $configs = array(
                        array( 'config_key'     => 'google_authenticator',
                               'group_name'     => 'general', 
                               'config_value'   => '0'),
                        array( 'config_key'     => 'private_site',
                               'group_name'     => 'general', 
                               'config_value'   => '0'),
                        array( 'config_key'     => 'private_site_page   ',
                               'group_name'     => 'general', 
                               'config_value'   => ''),
                        );
        
        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.2.0
     */
    public function action_230()
    {
        //configs for SMTP
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."config` (`group_name`, `config_key`, `config_value`) VALUES ('email', 'smtp_secure', (SELECT IF(config_value=0,'','ssl') as config_value FROM `".self::$db_prefix."config`as oconf WHERE `config_key` = 'smtp_ssl' AND `group_name`='email' LIMIT 1) );")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"DELETE FROM `".self::$db_prefix."config` WHERE `config_key` = 'smtp_ssl' AND `group_name`='email' LIMIT 1;")->execute();
        }catch (exception $e) {}

        //add new device_id for license
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."licenses` ADD `device_id` varchar(255) DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        //new mails
        $contents = array(array('order'=>0,
                                'title'=>'There is a new reply on the forum',
                               'seotitle'=>'new-forum-answer',
                               'description'=>"There is a new reply on a forum post where you participated.<br><br><a target=\"_blank\" href=\"[FORUM.LINK]\">Check it here</a><br><br>[FORUM.LINK]<br>",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                        );

        Model_Content::content_array($contents);
    }

    public function action_220()
    {
        //remove innodb
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."categories` ENGINE = MyISAM")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."users` ENGINE = MyISAM")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(
                        array( 'config_key'     => 'paysbuy',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'paysbuy_sandbox',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'cron',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        );
        
        Model_Config::config_array($configs);  
    }

    public function action_210()
    {
        
        //add new order fields
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `amount_net`  DECIMAL(14,3) NOT NULL DEFAULT '0' AFTER `amount`;")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `gateway_fee` DECIMAL(14,3) NOT NULL DEFAULT '0' AFTER `amount_net`;")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `VAT_amount`  DECIMAL(14,3) NOT NULL DEFAULT '0' AFTER `VAT`;")->execute();
        }catch (exception $e) {}

        //make posts bigger description
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."posts` CHANGE `description` `description` LONGTEXT;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."content` CHANGE `description` `description` LONGTEXT;")->execute();
        }catch (exception $e) {}

        //bigger configs
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."config` CHANGE `config_value` `config_value` LONGTEXT;")->execute();
        }catch (exception $e) {}

        //recalculate all the orders
        $orders = new Model_Order();
        $orders = $orders->where('status','=', Model_Order::STATUS_PAID)->where('amount_net','=',0)->find_all();

        foreach ($orders as $order) 
        {
            if ($order->paymethod=='stripe')
                $order->gateway_fee = StripeKO::calculate_fee($order->amount);
            elseif ($order->paymethod=='2checkout')
                $order->gateway_fee = Twocheckout::calculate_fee($order->amount);
            elseif ($order->paymethod=='paymill')
                $order->gateway_fee = Paymill::calculate_fee($order->amount);
            elseif ($order->paymethod=='authorize')
                $order->gateway_fee = Controller_Authorize::calculate_fee($order->amount);
            elseif ($order->paymethod=='paypal')//we dont have the history of the transactions so we clculate an aproximation using 4%
                $order->gateway_fee =  (4 * $order->amount / 100);
            else
                $order->gateway_fee = 0;
           
            //get VAT paid
            if ($order->VAT > 0)
                $order->VAT_amount = $order->amount - (100*$order->amount)/(100+$order->VAT);
            else
                $order->VAT_amount = 0;

            //calculate net amount
            $order->amount_net = $order->amount - $order->gateway_fee - $order->VAT_amount;

            try {
                $order->save();
            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());  
            }

        }

        //new configs
        $configs = array(
                        array( 'config_key'     => 'stripe_alipay',
                               'group_name'     => 'payment', 
                               'config_value'   => '0'),
                        array( 'config_key'     => 'captcha',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'recaptcha_active',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'recaptcha_secretkey',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'recaptcha_sitekey',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        );
        
        Model_Config::config_array($configs);        
    }

    public function action_200()
    {
        //new configs
        $configs = array(
                        array( 'config_key'     =>'twocheckout_sid',
                               'group_name'     =>'payment', 
                               'config_value'   => ''),
                        array( 'config_key'     =>'twocheckout_secretword',
                               'group_name'     =>'payment', 
                               'config_value'   => ''),
                        array( 'config_key'     =>'twocheckout_sandbox',
                               'group_name'     =>'payment', 
                               'config_value'   => 0),
                        );
        
        Model_Config::config_array($configs);
        
        //new mails
        $contents = array(array('order'=>0,
                                'title'=>'Password Changed [SITE.NAME]',
                                'seotitle'=>'password-changed',
                                'description'=>"Hello [USER.NAME],\n\nYour password has been changed.\n\nThese are now your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!",
                                'from_email'=>core::config('email.notify_email'),
                                'type'=>'email',
                                'status'=>'1'),
                        );
        
        Model_Content::content_array($contents);
    }

    public function action_190()
    {
        //new configs
        $configs = array(
                        array( 'config_key'     =>'api_key',
                               'group_name'     =>'general', 
                               'config_value'   => Text::random('alnum', 32)),
                        );
        
        Model_Config::config_array($configs);
        

        //api token
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `api_token` varchar(40) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD CONSTRAINT `oc2_users_UK_api_token` UNIQUE (`api_token`)")->execute();
        }catch (exception $e) {}    
        
    }

    public function action_181()
    {
         //new configs
        $configs = array(
                        array( 'config_key'     =>'fraudlabspro',
                               'group_name'     =>'payment', 
                               'config_value'   => ''),
                        );
        
        Model_Config::config_array($configs);
    }

    public function action_180()
    {
         //new configs
        $configs = array(
                        array( 'config_key'     =>'cookie_consent',
                               'group_name'     =>'general', 
                               'config_value'   => 0),
                        );
        
        Model_Config::config_array($configs);
    }

    public function action_171()
    {
    }

    /**
     * This function will upgrade configs
     */
    public function action_170()
    {
        //deleted classes moved to common
        File::delete(DOCROOT.'oc/classes/bitpay.php');
        File::delete(DOCROOT.'oc/classes/paymill.php');
        File::delete(DOCROOT.'oc/classes/stripeko.php');
        File::delete(DOCROOT.'themes/default/views/pages/authorize/button.php');
        File::delete(DOCROOT.'themes/default/views/pages/bitpay/button_loged.php');
        File::delete(DOCROOT.'themes/default/views/pages/paymill/button_loged.php');


        //crontabs
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Unpaid Orders', '0 7 * * *', 'Model_Order::cron_unpaid', NULL, 'Notify by email unpaid orders 2 days after was created', 1);")->execute();
        }catch (exception $e) {}
        
        //url buy
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."products` ADD `url_buy` varchar(245) ;")->execute();
        }catch (exception $e) {}

        //control login attempts
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `last_failed` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}
        
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `failed_attempts` int(10) unsigned DEFAULT 0")->execute();
        }catch (exception $e) {}
        
        //EU VAT
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users`  ADD `VAT_number` VARCHAR(65) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users`   ADD `country` VARCHAR(3) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `city` VARCHAR(65) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users`  ADD `postal_code` VARCHAR(20) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `address` VARCHAR(150) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //eu vat orders
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders`  ADD `VAT` decimal(14,3) NOT NULL DEFAULT '0.000'")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders`  ADD `VAT_number` VARCHAR(65) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders`   ADD `country` VARCHAR(3) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `city` VARCHAR(65) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders`  ADD `postal_code` VARCHAR(20) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `address` VARCHAR(150) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //categories/users has_image/last_modified
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` ADD `last_modified` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}
        
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` ADD `has_image` TINYINT( 1 ) NOT NULL DEFAULT '0' ;")->execute();
        }catch (exception $e) {}
            
        try 
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `has_image` TINYINT( 1 ) NOT NULL DEFAULT '0' ;")->execute();
        }catch (exception $e) {}
            
        //configs
        $configs = array(
                        array( 'config_key'     =>'aws_s3_active',
                               'group_name'     =>'image',
                               'config_value'   => 0),
                        array( 'config_key'     =>'aws_access_key',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'aws_secret_key',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'aws_s3_bucket',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'aws_s3_domain',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'html_head',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'html_footer',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'custom_css',
                               'group_name'     =>'appearance',
                               'config_value'   => 0),
                        array( 'config_key'     =>'custom_css_version',
                               'group_name'     =>'appearance',
                               'config_value'   => 0),
                        array( 'config_key'     =>'eu_vat',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array( 'config_key'     =>'vat_number',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'company_name',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'vat_excluded_countries',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        );
        
        Model_Config::config_array($configs);

        //new mails
        $contents = array(array('order'=>0,
                                'title'=>'Receipt for [ORDER.DESC] #[ORDER.ID]',
                               'seotitle'=>'new-order',
                               'description'=>"Hello [USER.NAME],Thanks for buying [ORDER.DESC].\n\nPlease complete the payment here [URL.CHECKOUT]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                        );

        Model_Content::content_array($contents);

        //upgrade has_image field to use it as images count
        $products = new Model_Product();
        $products = $products->where('has_images','=',0)->find_all();
        
        if(count($products))
        {
            foreach ($products as $product) 
            {
                $product->has_images = 0;//begin with 0 images
                $route = $product->image_path();
                $folder = DOCROOT.$route;
                $image_keys = array();
                
                if(is_dir($folder))
                {
                    //retrive ad pictures
                    foreach (new DirectoryIterator($folder) as $file) 
                    {   
                        if(!$file->isDot())
                        {   
                            $key = explode('_', $file->getFilename());
                            $key = end($key);
                            $key = explode('.', $key);
                            $key = (isset($key[0])) ? $key[0] : NULL ;
                            if(is_numeric($key))
                            {
                                if (strpos($file->getFilename(), 'thumb_') === 0)
                                {
                                    $image_keys[] = $key;
                                }
                            }
                        }
                    }
                    
                    //count images and reordering file names
                    
                    if (count($image_keys))
                    {
                        asort($image_keys);
                        
                        foreach ($image_keys as $image_key)
                        {
                            $product->has_images++;
                            
                            @rename($folder.$product->seotitle.'_'.$image_key.'.jpg', $folder.$product->seotitle.'_'.$product->has_images.'.jpg');
                            @rename($folder.'thumb_'.$product->seotitle.'_'.$image_key.'.jpg', $folder.'thumb_'.$product->seotitle.'_'.$product->has_images.'.jpg');
                        }
                    }
                }
                
                //update has_images count
                try 
                {
                    $product->save();
                } 
                catch (Exception $e) 
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }
        }

        //TODO
        //update has images
        //upgrade categories has_image
        $images_path = DOCROOT.'images/categories';
        if(is_dir($images_path))
        {
            //retrive cat pictures
            foreach (new DirectoryIterator($images_path) as $file) 
            {   
                if($file->isFile())
                {   
                    $cat_name =  str_replace('.png','', $file->getFilename());
                    $cat = new Model_Category();
                    $cat->where('seoname','=',$cat_name)->find();
                    if ($cat->loaded())
                    {
                        $cat->has_image = 1;
                        $cat->save();
                    }
                }
            }
        }

        //update crontabs
    }

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
            $category->description = Text::bb2html($category->description,TRUE,FALSE);
            try {
                $category->save();
            } catch (Exception $e) {}
        }

        //content description to HTML
        $contents = new Model_Content();
        $contents = $contents->find_all();
        foreach ($contents as $content) 
        {
            $content->description = Text::bb2html($content->description,TRUE,FALSE);
            try {
                $content->save();
            } catch (Exception $e) {}
        }

        //blog description to HTML
        $posts =  new Model_Post();
		$posts = $posts->where('id_forum','IS',NULL)->find_all();
        foreach ($posts as $post) 
        {
            $post->description = Text::bb2html($post->description,TRUE,FALSE);
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
                         array('config_key'     =>'count_visits',
                               'group_name'     =>'product', 
                               'config_value'   => 1),

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