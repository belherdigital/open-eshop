<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sociual auth class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */


class Social {

    public static function get()
    {
        $config = json_decode(core::config('social.config'),TRUE);
        return (!is_array($config))? array():$config;
    }

    public static function get_providers()
    {
        $providers = self::get();

        return (isset($providers['providers']))?$providers['providers']:array();
    }

    public static function enabled_providers()
    {
        $providers         = self::get();
        $enabled_providers = array();

        if (isset($providers['providers']))
        {
            foreach ($providers['providers'] as $k => $provider) {
                if ($provider['enabled'])
                {
                    $enabled_providers[$k] = $providers['providers'][$k];
                }
            }
        }

        return $enabled_providers;
    }

    public static function include_vendor()
    {
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Endpoint','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Logger','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Exception','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
        require_once Kohana::find_file('vendor', 'hybridauth/vendor/autoload','php');
    }

    public static function include_vendor_twitter()
    {
        require_once Kohana::find_file('vendor/', 'codebird-php/codebird');
    }

    public static function social_post_featured_ad(Model_Ad $ad)
    {
    	if($ad->status == Model_Ad::STATUS_PUBLISHED AND core::config('advertisement.social_post_only_featured') == TRUE)
    	{	
	        if(core::config('advertisement.twitter'))
	            self::twitter($ad);
	        
	        if(core::config('advertisement.facebook'))
	            self::facebook($ad);
    	}
    }

    public static function post_ad(Model_Ad $ad)
    {
    	if($ad->status == Model_Ad::STATUS_PUBLISHED AND core::config('advertisement.social_post_only_featured') == FALSE)
    	{	
	        if(core::config('advertisement.twitter'))
	            self::twitter($ad);
	        
	        if(core::config('advertisement.facebook'))
	            self::facebook($ad);
    	}
    }

    public static function twitter(Model_Ad $ad)
    {
        self::include_vendor_twitter();

        Codebird::setConsumerKey(core::config('advertisement.twitter_consumer_key'), core::config('advertisement.twitter_consumer_secret'));
        $cb = Codebird::getInstance();
        $cb->setToken(core::config('advertisement.access_token'), core::config('advertisement.access_token_secret'));

        // 'status' char limit is 140

        $message = Text::limit_chars($ad->title, 20, NULL, TRUE).', ';

        if($ad->category->id_category_parent != 1 AND $ad->category->parent->loaded())
            $message .= Text::limit_chars($ad->category->parent->name, 20, NULL, TRUE);

        $message .= ' - '.Text::limit_chars($ad->category->name, 20, NULL, TRUE);

        if($ad->id_location != 1)
        {
            if($ad->location->id_location_parent != 1 AND $ad->location->parent->loaded())
                $message .= ', '.Text::limit_chars($ad->location->parent->name, 20, NULL, TRUE);
            
            $message .= ' - '.Text::limit_chars($ad->location->name, 20, NULL, TRUE);
        }

        if($ad->price>0)
            $message .= ', '.i18n::money_format($ad->price);

        $url_ad = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));
        $message .= ' - '.$url_ad;

        $params = array(
            'status' => $message
        );

        if(isset($ad->latitude) AND $ad->latitude!='' AND isset($ad->longitude) AND $ad->longitude!=''){    
            $params['lat'] = $ad->latitude;
            $params['long'] = $ad->longitude;
        }

        $reply = $cb->statuses_update($params);
    }

    public static function facebook(Model_Ad $ad)
    {
        $page_access_token = core::config('advertisement.facebook_access_token');
        $page_id = core::config('advertisement.facebook_id');
        $app_secret = core::config('advertisement.facebook_app_secret');

        $appsecret_proof = hash_hmac('sha256', $page_access_token, $app_secret); 

        $url_ad = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));

        $description = $ad->description;

        if($ad->price>0)
            $description .= ' - '.__('Price').': '.i18n::money_format($ad->price);

        $message = $ad->title.', ';

        if($ad->category->id_category_parent != 1 AND $ad->category->parent->loaded())
            $message .= $ad->category->parent->name;

        $message .= ' - '.$ad->category->name;

        if($ad->id_location != 1)
        {
            if($ad->location->id_location_parent != 1 AND $ad->location->parent->loaded())
                $message .= ', '.$ad->location->parent->name;
            
            $message .= ' - '.$ad->location->name;
        }

        $data['link'] = $url_ad;
        $data['message'] = $message;
        $data['caption'] = core::config('general.base_url').' | '.core::config('general.site_name');
        $data['description'] = $description;

        $data['access_token'] = $page_access_token;

        $post_url = 'https://graph.facebook.com/me/feed?appsecret_proof='.$appsecret_proof.'&scope=publish_stream,status_update';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);

    }

    public static function GetAccessToken()
    {
        $token_url = "https://graph.facebook.com/oauth/access_token?client_id=".core::config('advertisement.facebook_app_id')."&client_secret=".core::config('advertisement.facebook_app_secret')."&grant_type=fb_exchange_token&fb_exchange_token=".core::config('advertisement.facebook_access_token');

        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_URL, $token_url);
        $contents = curl_exec($c);
        $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
        curl_close($c);

        $paramsfb = null;
        parse_str($contents, $paramsfb);  

        if($err == '200')
            model_config::set_value('advertisement','facebook_access_token',$paramsfb['access_token']);
        else
            model_config::set_value('advertisement','facebook_access_token','');
    }

}
