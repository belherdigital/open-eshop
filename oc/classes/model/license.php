<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User licenses
 *
 * @author      Chema <chema@open-classifieds.com>
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
     * get a licese @todo
     * @param  string $license 
     * @return model_license          
     */
    public static function get_license($license_num)
    {
        $license = new self();
        $license->where('license','=',$license_num)
                ->where('status', '=', Model_License::STATUS_ACTIVE)
                ->limit(1)->find();

        return $license;
    }


    /**
     * verifies a licese @todo
     * @param  string $license 
     * @param  string $domain name 
     * @return bool          
     */
    public static function verify($license_num,$domain)
    {
        //removing the www. so we accept both for same domain
        //$domain = preg_replace('#^www\.(.+\.)#i', '$1', $domain);
        //since 1.6 licenses are for an entire domain
        $domain = URL::get_domain($domain);

        $license = self::get_license($license_num);

        if ($license->loaded() AND !empty($domain))
        {
            //this means the license was at some point activated
            if ($license->active_date!=NULL AND $license->active_date!='' AND $license->domain!='')
            {
                //if license expired return false
                if ($license->valid_date!=NULL AND $license->valid_date!='' AND Date::mysql2unix($license->valid_date)<time() )
                    return FALSE;
                //check domain for the license. if matched
                if (URL::get_domain($license->domain) != $domain)
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
     * verifies a license in a certain device
     * @param  string $license 
     * @param  string $device_id name 
     * @return bool          
     */
    public static function verify_device($license_num,$device_id)
    {
        $license = self::get_license($license_num);

        if ($license->loaded())
        {
            //this means the license was at some point activated
            if ($license->active_date!=NULL AND $license->active_date!='')
            {
                //if license expired return false
                if ($license->valid_date!=NULL AND $license->valid_date!='' AND Date::mysql2unix($license->valid_date)<time() )
                    return FALSE;
                //check device_id for the license. if matched
                if ($license->device_id != $device_id)
                    return FALSE;
            }
            //if license not active we activate it
            else
            {
                $license->active_date   = Date::unix2mysql();
                $license->device_id     = $device_id;
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
    public static function generate($user, Model_Order $order, Model_Product $product)
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