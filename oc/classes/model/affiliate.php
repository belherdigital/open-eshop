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
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_CREATED      =>  'Created',
        self::STATUS_PAID         =>  'Paid',
        self::STATUS_REFUSED      =>  'Refused',
        self::STATUS_FRAUD       =>  'Fraud',
        self::STATUS_REFUND       =>  'Refund',
    );
    
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
     * global Model user affiliate instance get from controller so we can access from anywhere like Model_Affiliate::current()
     * @var Model_User
     */
    protected static $_current = NULL;

    /**
     * returns the current affiliate user
     * @return Model_User 
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
            self::$_current = self::get_affiliate();

        return self::$_current;
    }

    /**
     * @var string cookie name
     */
    protected static $_cookie_name = 'affiliate';

    /**
     * get the affiliate from the query or from the cookie
     * @return Model_Affiliate
     */
    public static function get_affiliate()
    {
        $id_affiliate = core::request('aff',Cookie::get(self::$_cookie_name));
        $affiliate    = new Model_User();

        if (Core::config('affiliate.active')==1 AND is_numeric($id_affiliate) AND Theme::get('premium')==1)
        {
            $affiliate = new Model_User($id_affiliate);

            //the user exists so we set again the cookie, just in case it's a different user or to renew it
            if ($affiliate->loaded())
                Cookie::set(self::$_cookie_name,$id_affiliate, time() + (24*60*60*Core::config('affiliate.cookie')) );                
        }

        return $affiliate;
    }

    /**
     * generates a new commission for the affiliate
     * @param  Model_Order $order   
     * @param  Model_Product      $product [description]
     * @return void               
     */
    public static function sale(Model_Order $order, Model_Product $product = NULL)
    {
        //do we have an affiliate?
        if (self::current()->loaded())
        {
            if ($product === NULL)
                $product = $order->product;

            //this is how much we actually pay to the affiliate
            $commission = ($order->amount/100)*$product->affiliate_percentage;

            //doesnt make sense to add a commission of 0,no?
            if ($commission>0)
            {
                $aff = new self();
                $aff->id_order      = $order->id_order;
                $aff->id_product    = $product->id_product;
                $aff->id_user       = self::current()->id_user;
                $aff->percentage    = $product->affiliate_percentage;
                $aff->currency      = $product->currency;
                $aff->amount        = $commission;
                $aff->date_to_pay   = Date::unix2mysql(time()+(24*60*60*core::config('affiliate.payment_days')));
                $aff->ip_address    = ip2long(Request::$client_ip);
                $aff->status        = Model_Affiliate::STATUS_CREATED;

                try
                {
                    $aff->save();

                    //send email to affiliate
                    $params = array(
                                    '[AMOUNT]'        => i18n::format_currency($commission, $product->currency),
                                    '[URL.AFF]'       => self::current()->ql('oc-panel',array('controller'=>'profile','action'=>'affiliate')),
                                );
                    self::current()->email('affiliate-commission',$params);
                    
                }
                catch (Exception $e)
                {
                    Kohana::$log->add(Log::ERROR,$e->getMessage());
                } 
            }
            
                    
        }

        
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
        $form->fields['status']['display_as']       = 'select';
        $form->fields['status']['options']           = array_keys(self::$statuses);
    }

    public function exclude_fields()
    {
        return array();
    }




}