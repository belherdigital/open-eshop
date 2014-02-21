<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User downloads
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Download extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'downloads';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_download';


    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
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
       
    }

    public function exclude_fields()
    {
    
    }

   

    /**
     * creates new download
     * @param  Model_User    $user    
     * @param  Model_Order   $order   
     * @return string                 
     */
    public static function generate(Model_User $user, Model_Order $order)
    {
        $download = new self();
        $download->id_user      = $user->id_user;
        $download->id_order     = $order->id_order;
        $download->ip_address   = ip2long(Request::$client_ip);
        $download->save();

        return $download;
    }



}