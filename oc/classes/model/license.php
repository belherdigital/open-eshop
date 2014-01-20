<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User licenses
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_License extends ORM {

     /**
     * status constants
     */
    const STATUS_NOACTIVE = 0; 
    const STATUS_ACTIVE   = 1; 
  
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'licenses';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_license';


    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'product' => array(
                'model'       => 'product',
                'foreign_key' => 'id_product',
            ),
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'order' => array(
                'model'       => 'order',
                'foreign_key' => 'id_order',
            ),
    );

    public function form_setup($form)
    {
       $form->fields['id_user']['display_as']      = 'text';
       $form->fields['id_order']['display_as']      = 'text';
    }

    public function exclude_fields()
    {
    
    }

    /**
     * verifies a licese @todo
     * @param  string $license 
     * @return bool          
     */
    public static function verify($license_num,$domain)
    {
        //removing the www. so we accept both for same domain
        $domain = preg_replace('#^www\.(.+\.)#i', '$1', $domain);

        $license = new self();
        $license->where('license','=',$license_num)
                ->where('status', '=', Model_License::STATUS_ACTIVE)
                ->limit(1)->find();

        if ($license->loaded())
        {
            //this means the license was at some point activated
            if ($license->active_date!=NULL AND $license->active_date!='')
            {
                //if license expired return false
                if ($license->valid_date!=NULL AND $license->valid_date!='' AND Date::mysql2unix($license->valid_date)<time() )
                    return FALSE;
                //check domain for the license. if matched
                if ($license->domain != $domain)
                    return FALSE;
            }
            //if license not active we activate it
            else
            {
                $license->active_date   = Date::unix2mysql();
                $license->domain        = $domain;
            }

            $license->license_check_date = Date::unix2mysql();
            $license->ip_address         = ip2long(Request::$client_ip);
            $license->save();
            
            return TRUE;
        }

        return FALSE;
    }

    /**
     * creates the licenses for the purchase
     * @param  Model_User    $user    
     * @param  Model_Order   $order   
     * @param  Model_Product $product 
     * @return string                 
     */
    public static function generate(Model_User $user, Model_Order $order, Model_Product $product)
    {
        $license = date('Ymd').'-'.$order->id_order.'-';

        //until when the license is valid/expires
        if ($product->license_days>0)
            $license_valid = Date::unix2mysql(strtotime('+'.$product->license_days.' day'));
        else 
            $license_valid = NULL;//never expires

        //we create a license for amount specified on product
        for ($i=0; $i < $product->licenses; $i++) 
        { 
            $l = new self();
            $l->id_user       = $user->id_user;
            $l->id_product    = $product->id_product;
            $l->id_order      = $order->id_order;
            $l->license       = $license.strtoupper(Text::random('alnum', 40-strlen($license)));
            $l->valid_date    = $license_valid;
            $l->status        = self::STATUS_ACTIVE;
            $l->save();
        }

        $licenses = new self();
        $licenses = $licenses->where('id_user','=',$user->id_user)
                    ->where('id_order','=',$order->id_order)
                    ->find_all();

        return $licenses;
    }



}

/*
 * Name:    Open eShop API
 * URL:     http://open-eshop.com
 * Version: 0.1
 * Date:    18/10/2013
 * Author:  Chema Garrido
 * License: GPL v3
 * Notes:   API Class for open-eshop.com
 */
class eshop{
    
    /**
     * URL where we check the license @todo modify this!!!
     * @var string
     */
    private static $api_url = 'http://eshop.lo/api/license/';
    private static $timeout = 5;//timeout for the request
    
    //sends the request to the server, uses curl
    public static function license($license)
    {
        $ch = curl_init();
        if ($ch)
        {
            curl_setopt($ch, CURLOPT_URL,self::$api_url.$license) ;
            curl_setopt($ch, CURLOPT_POST, 1 ) ;
            curl_setopt($ch, CURLOPT_POSTFIELDS,'&domain='.$_SERVER['SERVER_NAME']);
            curl_setopt($ch, CURLOPT_TIMEOUT,self::$timeout); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $server_output = curl_exec ($ch);
            curl_close ($ch); 
            
            return $server_output;
        }
        else return FALSE;
    }
    //end send request
}