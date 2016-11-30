<?php
/**
 * SQL installation import
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

defined('SYSPATH') or exit('Install must be loaded from within index.php!');

mysqli_query($link,'SET NAMES '.core::request('DB_CHARSET'));
mysqli_query($link,"SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");

mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."roles_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS  `".core::request('TABLE_PREFIX')."users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `seoname` varchar(145) DEFAULT NULL,
  `email` varchar(145) NOT NULL,
  `paypal_email` varchar(145) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `description` text NULL DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `id_role` int(10) unsigned DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` datetime DEFAULT NULL,
  `logins` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_ip`  bigint DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `api_token` varchar(40) DEFAULT NULL,
  `hybridauth_provider_name` varchar(40) NULL DEFAULT NULL,
  `hybridauth_provider_uid` varchar(245) NULL DEFAULT NULL,
  `signature` varchar(245) NULL DEFAULT NULL,
  `subscriber` tinyint(1) NOT NULL DEFAULT '1',
  `has_image` tinyint(1) NOT NULL DEFAULT '0',
  `failed_attempts` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_failed` datetime DEFAULT NULL,
  `VAT_number` VARCHAR(65) NULL DEFAULT NULL,
  `country` VARCHAR(3) NULL DEFAULT NULL,
  `city` VARCHAR(65) NULL DEFAULT NULL,
  `postal_code` VARCHAR(20) NULL DEFAULT NULL,
  `address` VARCHAR(150) NULL DEFAULT NULL,
  `google_authenticator` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."users_UK_email` (`email`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."users_UK_token` (`token`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."users_UK_api_token` (`api_token`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."users_UK_seoname` (`seoname`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."users_UK_provider_AND_uid` (`hybridauth_provider_name`,`hybridauth_provider_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS  `".core::request('TABLE_PREFIX')."categories` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_category_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` text NULL,
  `last_modified` DATETIME  NULL,
  `has_image` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`) USING BTREE,
  UNIQUE KEY `".core::request('TABLE_PREFIX')."categories_UK_seo_name` (`seoname`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `id_affiliate` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` bigint DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `".core::request('TABLE_PREFIX')."visits_IK_id_user` (`id_user`),
  KEY `".core::request('TABLE_PREFIX')."visits_IK_id_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` LONGTEXT,
   PRIMARY KEY (`config_key`),
   UNIQUE KEY `".core::request('TABLE_PREFIX')."config_IK_group_name_AND_config_key` (`group_name`,`config_key`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET')." ;");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) NOT NULL DEFAULT 'en_US',
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` LONGTEXT NULL,
  `from_email` varchar(145) NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('page','email','help') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


//////////////Tables for eShop

mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."products` (
  `id_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned DEFAULT NULL,
  `id_category` int(10) unsigned DEFAULT NULL,
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `skins` varchar(245) NOT NULL,
  `url_demo` varchar(145) NOT NULL,
  `url_buy` varchar(245),
  `description` text NOT NULL,
  `email_purchase_notes` text NOT NULL,
  `version` varchar(10) NOT NULL,
  `currency` char(3) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0',
  `price_offer` decimal(10,2) NOT NULL DEFAULT '0',
  `offer_valid` DATETIME  NULL,
  `featured` DATETIME  NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_images` tinyint(1) NOT NULL DEFAULT '0',
  `file_name` varchar(40) DEFAULT NULL,
  `support_days` int(10)  NOT NULL DEFAULT '0',
  `licenses` int(10)  NOT NULL DEFAULT '1',
  `license_days` int(10)  NOT NULL DEFAULT '0',
  `rate` FLOAT( 4, 2 ) NULL DEFAULT NULL,
  `affiliate_percentage` decimal(14,3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product`),
  KEY `".core::request('TABLE_PREFIX')."products_IK_id_user` (`id_user`),
  KEY `".core::request('TABLE_PREFIX')."products_IK_id_category` (`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS  `".core::request('TABLE_PREFIX')."orders` (
  `id_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NULL,
  `id_coupon` int(10) unsigned NULL,
  `paymethod` VARCHAR(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `currency` char(3) NOT NULL,
  `amount` DECIMAL(14,3) NOT NULL DEFAULT '0',
  `amount_net` DECIMAL(14,3) NOT NULL DEFAULT '0',
  `gateway_fee` DECIMAL(14,3) NOT NULL DEFAULT '0',
  `ip_address` bigint DEFAULT NULL,
  `txn_id` VARCHAR(255) DEFAULT NULL,
  `pay_date` DATETIME  NULL,
  `support_date` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `notes` VARCHAR( 245 ) NULL DEFAULT NULL,
  `VAT` DECIMAL(14,3) NOT NULL DEFAULT '0.000',
  `VAT_amount`  DECIMAL(14,3) NOT NULL DEFAULT '0',
  `VAT_number` VARCHAR(65) NULL DEFAULT NULL,
  `country` VARCHAR(3) NULL DEFAULT NULL,
  `city` VARCHAR(65) NULL DEFAULT NULL,
  `postal_code` VARCHAR(20) NULL DEFAULT NULL,
  `address` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id_order`),
  KEY `".core::request('TABLE_PREFIX')."orders_IK_id_user` (`id_user`),
  KEY `".core::request('TABLE_PREFIX')."orders_IK_status` (`status`)
)ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."licenses` (
  `id_license` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_order` int(10) unsigned NOT NULL,
  `license` varchar(40) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `ip_address` bigint DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_date` DATETIME  NULL,
  `valid_date` DATETIME  NULL,
  `license_check_date` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_license`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."licenses_UK_license` (`license`),
  KEY `".core::request('TABLE_PREFIX')."licenses_IK_id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."downloads` (
  `id_download` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` bigint DEFAULT NULL,
  PRIMARY KEY (`id_download`),
  KEY `".core::request('TABLE_PREFIX')."downloads_IK_id_user` (`id_user`),
  KEY `".core::request('TABLE_PREFIX')."downloads_IK_id_order` (`id_order`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");



mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."tickets` (
  `id_ticket` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ticket_parent` int(10) unsigned NULL,
  `id_product` int(10) unsigned NOT NULL,
  `id_order` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_user_support` int(10) unsigned NULL,
  `title` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_date` DATETIME  NULL,
  `ip_address` bigint DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ticket`),
  KEY `".core::request('TABLE_PREFIX')."tickets_IK_id_user` (`id_user`),
  KEY `".core::request('TABLE_PREFIX')."tickets_IK_id_ticket_parent` (`id_ticket_parent`),
  KEY `".core::request('TABLE_PREFIX')."tickets_IK_id_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS `".core::request('TABLE_PREFIX')."coupons` (
  `id_coupon` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(10) unsigned NULL DEFAULT NULL,
  `name` varchar(145) NOT NULL,
  `notes` varchar(245) DEFAULT NULL,
  `discount_amount` decimal(14,3) NOT NULL DEFAULT '0',
  `discount_percentage` decimal(14,3) NOT NULL DEFAULT '0',
  `number_coupons` int(10) DEFAULT NULL,
  `valid_date` DATETIME  NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_coupon`),
  UNIQUE KEY `".core::request('TABLE_PREFIX')."coupons_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS  `".core::request('TABLE_PREFIX')."posts` (
  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_post_parent` int(10) unsigned NULL DEFAULT NULL,
  `id_forum` int(10) unsigned NULL DEFAULT NULL,
  `title` varchar(245) NOT NULL,
  `seotitle` varchar(245) NOT NULL,
  `description` longtext NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` bigint DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_post`) USING BTREE,
  UNIQUE KEY `".core::request('TABLE_PREFIX')."posts_UK_seotitle` (`seotitle`),
  KEY `".core::request('TABLE_PREFIX')."posts_IK_id_user` (`id_user`),
  KEY `".core::request('TABLE_PREFIX')."posts_IK_id_post_parent` (`id_post_parent`),
  KEY `".core::request('TABLE_PREFIX')."posts_IK_id_forum` (`id_forum`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");


mysqli_query($link,"CREATE TABLE IF NOT EXISTS  `".core::request('TABLE_PREFIX')."forums` (
  `id_forum` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_forum_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  PRIMARY KEY (`id_forum`) USING BTREE,
  UNIQUE KEY `".core::request('TABLE_PREFIX')."forums_UK_seo_name` (`seoname`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");

mysqli_query($link,"CREATE TABLE IF NOT EXISTS ".core::request('TABLE_PREFIX')."reviews (
    id_review int(10) unsigned NOT NULL AUTO_INCREMENT,
    id_user int(10) unsigned NOT NULL,
    id_order int(10) unsigned NOT NULL,
    id_product int(10) unsigned NOT NULL,
    rate int(2) unsigned NOT NULL DEFAULT '0',
    description varchar(1000) NOT NULL,
    created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_address bigint DEFAULT NULL,
    status tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id_review) USING BTREE,
    KEY ".core::request('TABLE_PREFIX')."reviews_IK_id_user (id_user),
    KEY ".core::request('TABLE_PREFIX')."reviews_IK_id_order (id_order),
    KEY ".core::request('TABLE_PREFIX')."reviews_IK_id_product (id_product)
    ) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");

mysqli_query($link,"CREATE TABLE IF NOT EXISTS ".core::request('TABLE_PREFIX')."affiliates (
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
    ip_address bigint DEFAULT NULL,
    status tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id_affiliate) USING BTREE,
    KEY ".core::request('TABLE_PREFIX')."affiliates_IK_id_user (id_user),
    KEY ".core::request('TABLE_PREFIX')."affiliates_IK_id_order (id_order),
    KEY ".core::request('TABLE_PREFIX')."affiliates_IK_id_product (id_product)
    ) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");

mysqli_query($link,"CREATE TABLE IF NOT EXISTS  `".core::request('TABLE_PREFIX')."crontab` (
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
  UNIQUE KEY `".core::request('TABLE_PREFIX')."crontab_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=".core::request('DB_CHARSET').";");

/**
 * add basic content like emails
 */
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."content` (`order`, `title`, `seotitle`, `description`, `from_email`, `type`, `status`) 
    VALUES
(0, 'Change Password [SITE.NAME]', 'auth-remember', 'Hello [USER.NAME],\n\nFollow this link  [URL.QL]\n\nThanks!!', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Welcome to [SITE.NAME]!', 'auth-register', 'Welcome [USER.NAME],\n\nWe are really happy that you have joined us! [URL.QL]\n\nRemember your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, '[EMAIL.SENDER] wants to contact you!', 'contact-admin', 'Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.SUBJECT]\n\n [EMAIL.BODY] \n\n Regards!', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'New reply: [TITLE]', 'new-reply', '[URL.QL]\n\n[DESCRIPTION]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Purchase Receipt for [PRODUCT.TITLE]', 'new-sale', '==== Order Details ====\nDate: [DATE]\nOrder ID: [ORDER.ID]\nName: [USER.NAME]\nEmail: [USER.EMAIL]\n\n==== Your Order ====\nProduct: [PRODUCT.TITLE]\nProduct Price: [PRODUCT.PRICE]\n\n[PRODUCT.NOTES][DOWNLOAD][EXPIRE][LICENSE]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Product updated [TITLE]', 'product-update', '==== Update Details ====\nVersion: [VERSION]\nProduct name: [TITLE][DOWNLOAD][EXPIRE]\n\n==== Product Page ====\n[URL.PRODUCT]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Ticket assigned to you: [TITLE]', 'assign-agent', '[URL.QL]\n\n[DESCRIPTION]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'New review for [TITLE] [RATE]', 'review-product', '[URL.QL]\n\n[RATE]\n\n[DESCRIPTION]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'New support ticket created `[TITLE]`', 'new-ticket', 'We have received your support inquiry. We will try to answer you within the next 24 working hours, thank you for your patience.\n\n[URL.QL]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Congratulations! New affiliate commission [AMOUNT]', 'affiliate-commission', 'Congratulations!,\n\n We just registered a sale from your affiliate link for the amount of [AMOUNT], check them all at your affiliate panel [URL.AFF]. \n\n Thanks for using our affiliate program!', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Password Changed [SITE.NAME]', 'password-changed', 'Hello [USER.NAME],\n\nYour password has been changed.\n\nThese are now your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'Receipt for [ORDER.DESC] #[ORDER.ID]', 'new-order', 'Hello [USER.NAME],Thanks for buying [ORDER.DESC].\n\nPlease complete the payment here [URL.CHECKOUT]', '".core::request('ADMIN_EMAIL')."', 'email', 1),
(0, 'There is a new reply on the forum', 'new-forum-answer', 'There is a new reply on a forum post where you participated.<br><br><a target=\"_blank\" href=\"[FORUM.LINK]\">Check it here</a><br><br>[FORUM.LINK]<br>', '".core::request('ADMIN_EMAIL')."', 'email',  1);");


/**
 * Access
 */
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."roles` (`id_role`, `name`, `description`) VALUES (1, 'user', 'Normal user'), (5, 'translator', 'User + Translations'), (10, 'admin', 'Full access');");
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."access` (`id_access`, `id_role`, `access`) VALUES 
            (1, 10, '*.*'),
            (2, 1, 'profile.*'),(3, 1, 'stats.user'),(8, 1, 'support.*'),
            (4, 5, 'translations.*'),(5, 5, 'profile.*'),(6, 5, 'stats.user'),(7, 5, 'content.*');");

/**
 * Create user God/Admin 
 */
$password = hash_hmac('sha256', core::request('ADMIN_PWD'), install::$hash_key);
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."users` (`id_user`, `name`, `seoname`, `email`, `password`, `status`, `id_role`) 
VALUES (1, 'admin', 'admin', '".core::request('ADMIN_EMAIL')."', '$password', 1, 10)");

/**
 * Configs to make the app work
 * @todo refactor to use same coding standard
 * @todo widgets examples? at least at sidebar, rss, text advert, pages, locations...
 *
 */
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."config` (`group_name`, `config_key`, `config_value`) VALUES
('appearance', 'theme', 'default'),
('appearance', 'theme_mobile', ''),
('appearance', 'allow_query_theme', 0),
('appearance', 'custom_css', 0),
('appearance', 'custom_css_version', 0),
('i18n', 'charset', 'utf-8'),
('i18n', 'timezone', '".core::request('TIMEZONE')."'),
('i18n', 'locale', '".core::request('LANGUAGE')."'),
('i18n', 'allow_query_language', 0),
('payment', 'thanks_page', ''),
('payment', 'sandbox', 0),
('payment', 'paypal_account', ''),
('payment', 'paymill_private', ''),
('payment', 'paymill_public', ''),
('payment', 'stripe_private', ''),
('payment', 'stripe_public', ''),
('payment', 'stripe_address', '0'),
('payment', 'stripe_alipay', '0'),
('payment', 'stripe_3d_secure', '0'),
('payment', 'alternative', ''),
('payment', 'bitpay_apikey', ''),
('payment', 'authorize_sandbox', '0'),
('payment', 'authorize_login', ''),
('payment', 'authorize_key', ''),
('payment', 'twocheckout_sid', ''),
('payment', 'twocheckout_secretword', ''),
('payment', 'twocheckout_sandbox', 0),
('payment', 'fraudlabspro', ''),
('payment', 'paysbuy', ''),
('payment', 'paysbuy_sandbox', '0'),
('payment', 'mercadopago_client_id', ''),
('payment', 'mercadopago_client_secret', ''),
('general', 'api_key', '".core::generate_password(32)."'),
('general', 'number_format', '%n'),
('general', 'date_format', 'd-m-y'),
('general', 'base_url', '".core::request('SITE_URL')."'),
('general', 'maintenance', 0),
('general', 'private_site', 0),
('general', 'private_site_page', ''),
('general', 'analytics', ''),
('general', 'translate', ''),
('general', 'menu', ''),
('general', 'feed_elements', '20'),
('general', 'site_name', '".core::request('SITE_NAME')."'),
('general', 'site_description', ''),
('general', 'products_per_page', '12'),
('general', 'akismet_key', ''),
('general', 'alert_terms', ''),
('general', 'landing_page', ''),
('general', 'blog', '0'),
('general', 'blog_disqus', ''),
('general', 'faq', '0'),
('general', 'faq_disqus', ''),
('general', 'forums', '0'),
('general', 'minify', 0),
('general', 'sort_by', 'published-asc'),
('general', 'ocacu', '0'),
('general', 'banned_words_replacement', 'xxx'),
('general', 'banned_words', ''),
('general', 'disallowbots', 0),
('general', 'html_head', ''),
('general', 'html_footer', ''),
('general', 'eu_vat', 0),
('general', 'vat_number', ''),
('general', 'company_name', ''),
('general', 'vat_excluded_countries', ''),
('general', 'cookie_consent', 0),
('general', 'captcha', ''),
('general', 'recaptcha_active', ''),
('general', 'recaptcha_secretkey', ''),
('general', 'recaptcha_sitekey', ''),
('general', 'cron', 1),
('general', 'google_authenticator', 0),
('image', 'allowed_formats', 'jpeg,jpg,png,'),
('image', 'max_image_size', '5'),
('image', 'height', ''),
('image', 'width', '1200'),
('image', 'height_thumb', '200'),
('image', 'width_thumb', '200'),
('image', 'quality', '90'),
('image', 'watermark', '0'),
('image', 'watermark_path', ''),
('image', 'watermark_position', '0'),
('image', 'aws_s3_active', 0),
('image', 'aws_access_key', ''),
('image', 'aws_secret_key', ''),
('image', 'aws_s3_bucket', ''),
('image', 'aws_s3_domain', ''),
('product', 'formats', 'txt,doc,docx,pdf,tif,tiff,gif,psd,raw,wav,aif,mp3,rm,ram,wma,ogg,avi,wmv,mov,mp4,mkv,jpeg,jpg,png,zip,7z,7zip,rar,rar5,gzip,'),
('product', 'max_size', '5'),
('product', 'num_images', '5'),
('product', 'products_in_home', '0'),
('product', 'disqus', ''),
('product', 'related', '5'),
('product', 'reviews', '0'),
('product', 'demo_theme', 'yeti'),
('product', 'demo_resize', '1'),
('product', 'download_hours', 72),
('product', 'download_times', 3),
('product', 'number_of_orders', 0),
('product', 'qr_code', 0),
('product', 'count_visits', 1),
('email', 'notify_email', '".core::request('ADMIN_EMAIL')."'),
('email', 'notify_name', '"."no-reply ".core::request('SITE_NAME')."'),
('email', 'new_sale_notify', 0),
('email', 'smtp_active', 0),
('email', 'smtp_host', ''),
('email', 'smtp_port', ''),
('email', 'smtp_auth', 0),
('email', 'smtp_secure', ''),
('email', 'smtp_user', ''),
('email', 'smtp_pass', ''),
('email', 'elastic_active', 0),
('email', 'elastic_username', ''),
('email', 'elastic_password', ''),
('email', 'elastic_listname', ''),
('affiliate', 'active', '0'),
('affiliate', 'cookie', '90'),
('affiliate', 'payment_days', '30'),
('affiliate', 'payment_min', '50'),
('affiliate', 'tos', ''),
('social', 'config', '{\"debug_mode\":\"0\",\"providers\":{\"OpenID\":{\"enabled\":\"0\"},\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},
\"AOL\":{\"enabled\":\"0\"},\"Google\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},\"Facebook\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},
\"Twitter\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},\"Live\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},\"MySpace\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},
\"LinkedIn\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},\"Foursquare\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}}},\"base_url\":\"\",\"debug_file\":\"\"}');");


//base category
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."categories` 
  (`id_category` ,`name` ,`order` ,`id_category_parent` ,`parent_deep` ,`seoname` ,`description` )
VALUES (1, 'Home category', 0 , 0, 0, 'all', 'root category');");


//crontabs
mysqli_query($link,"INSERT INTO `".core::request('TABLE_PREFIX')."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
('Sitemap', '0 3 * * *', 'Sitemap::generate', NULL, 'Regenerates the sitemap everyday at 3am',1),
('Clean Cache', '0 5 * * *', 'Core::delete_cache', NULL, 'Once day force to flush all the cache.', 1),
('Optimize DB', '0 4 1 * *', 'Core::optimize_db', NULL, 'once a month we optimize the DB', 1),
('Unpaid Orders', '0 7 * * *', 'Cron_Order::unpaid', NULL, 'Notify by email unpaid orders 2 days after was created', 1);");
 


mysqli_close($link);