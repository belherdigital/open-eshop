<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product affiliates
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Affiliate extends ORM {


    /**
     * Status constants, same as orders
     */
    const STATUS_CREATED        = 0;   // just created
    const STATUS_PAID           = 1;   // paid!
    const STATUS_REFUSED        = 5;   //tried to paid but not succeed
    const STATUS_FRAUD          = 66;  //fraud!!!!
    const STATUS_REFUND         = 99;  //we refunded the money

    
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'affiliates';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_affiliate';

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

    /**
     * @var string cookie name
     */
    protected static $_cookie_name = 'affiliate';

    /**
     * get the affiliate from the query or from the cookie
     * @return Model_Affiliate or null if not found
     */
    public static function get_affiliate()
    {
        $id_affiliate = core::request('aff',Cookie::get(self::$_cookie_name));

        if (Core::config('affiliate.active')==1 AND is_numeric($id_affiliate) AND Theme::get('premium')==1)
        {
            $affiliate = new Model_User($id_affiliate);

            if ($affiliate->loaded())
            {
                //the user exists so we set again the cookie, just in case it's a different user or to renew it
                Cookie::set(self::$_cookie_name,$id_affiliate, time() + (24*60*60*Core::config('affiliate.cookie')) );
                return $affiliate;
            }
                
        }

        return NULL;
    }


    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   

        $form->fields['id_product']['display_as']   = 'select';
        $form->fields['id_product']['caption']      = 'title';  
        $form->fields['id_user']['display_as']      = 'text';
        $form->fields['id_order']['display_as']      = 'text';
    }




}