<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Slobodan <slobodan.josifovic@gmail.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Order extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'orders';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_order';

	/**
	 * Status constants
	 */
    const STATUS_CREATED        = 0;   // just created
    const STATUS_PAID           = 1;   // paid!
    const STATUS_REFUSED        = 5;   //tried to paid but not succeed
    const STATUS_REFUND         = 99;  //we refunded the money

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_CREATED      =>  'Created',
        self::STATUS_PAID         =>  'Paid',
        self::STATUS_REFUSED      =>  'Refused',
        self::STATUS_REFUND       =>  'Refund',
    );

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
    );

    protected $_has_many = array(
        'licenses' => array(
            'model'   => 'license',
            'foreign_key' => 'id_order',
        ),
    );

    public function exclude_fields()
    {
        return array('created','parent_deep','order');
    }
    
    /**
     * creates new orders for a product SOLD and generates the licenses
     * @param  integer        $id_order only used if order already exists
     * @param  Model_User    $user     
     * @param  Model_Product $product  
     * @param  string        $token    
     * @param  string        $method   
     * @return void                
     */
    public static function sale($id_order=NULL, Model_User $user, Model_Product $product, $token, $method = 'paypal')
    {
        $order = new Model_Order();

        //retrieve info for the item in DB
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_PAID)
                       ->limit(1)->find();

        //order didnt exists probably cuz is paypal and we generate the order only once paid
        if (!$order->loaded())
        {
            $order->id_product  = $product->id_product;
            $order->id_user     = $user->id_user;
            $order->paymethod   = $method;
            $order->currency    = $product->currency;
            $order->amount      = $product->price;
            $order->ip_address  = ip2long(Request::$client_ip);
        }

        $order->txn_id      = $token;
        $order->pay_date    = Date::unix2mysql();
        $order->status      = Model_Order::STATUS_PAID;

        try {
            $order->save();

            //generate licenses
            $licenses = Model_License::generate($user,$order,$product);

            //loop all the licenses

            //@todo
            //send email with order details download link and product notes 
            $user->email('new.sale',array( 
                                           '[LICENSE]' => $license,
                                           '[URL.QL]'=>$user->ql('default',NULL,TRUE)
                                        )
                                );

            //notify to seller
            if(core::config('email.new_sale_notify'))
            {
                Email::send(core::config('email.notify_email'), '', 'New Sale! '.$product->title, 'New Sale! '.$product->title, core::config('email.notify_email'), '');
            }

            return $order;
        } 
        catch (Exception $e) 
        {
            Kohana::$log->add(Log::ERROR, 'Order failed on creation, but paid. '.Core::post('payer_email'));
            d($e);
        }

        return FALSE;

    }

}