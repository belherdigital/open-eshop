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
       
    }

    public function exclude_fields()
    {
    
    }

    /**
     * verifies a licese @todo
     * @param  string $license 
     * @return bool          
     */
    public static function verify($license)
    {
        $license = new self();
        $license->where('license','=',$license)
                ->where('status', '=', Model_License::STATUS_ACTIVE)
                ->limit(1)->find();

        if ($license->loaded())
        {

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

        //we create a license for amount specified on product
        for ($i=0; $i < $product->licenses; $i++) 
        { 
            $l = new self();
            $l->id_user       = $user->id_user;
            $l->id_product    = $product->id_product;
            $l->id_order      = $order->id_order;
            $l->license       = $license.strtoupper(Text::random('alnum', 40-strlen($license)));
            $l->save();
        }

        $licenses = new self();
        $licenses = $licenses->where('id_user','=',$user->id_user)
                    ->where('id_order','=',$order->id_order)
                    ->find_all();

        return $licenses;
    }



}