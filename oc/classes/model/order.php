<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Slobodan <slobodan@open-classifieds.com>
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
        'coupon' => array(
                'model'       => 'coupon',
                'foreign_key' => 'id_coupon',
            ),
    );

    protected $_has_many = array(
        'licenses' => array(
            'model'   => 'license',
            'foreign_key' => 'id_order',
        ),
        'downloads' => array(
            'model'   => 'download',
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
     * @param  date          $pay_date Y-m-d H:i:s
     * @param  integer       $amount_paid
     * @param  string        $currency_paid    
     * @return void                
     */
    public static function sale($id_order = NULL, Model_User $user, Model_Product $product, 
                                $token, $method = 'paypal', $pay_date = NULL,$amount_paid = NULL, $currency_paid = NULL)
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
            $order->currency    = ($currency_paid==NULL)?$product->currency:$currency_paid;
            //add coupon ID and discount
            if (Controller::$coupon!=NULL)
                $order->id_coupon = Controller::$coupon->id_coupon;
            $order->amount      = ($amount_paid==NULL)?$product->final_price():$amount_paid;
            
            //paypal will put here his ip adress thats why we do not add it
            if ($method!=='paypal')
                $order->ip_address  = ip2long(Request::$client_ip);
        }

        $order->txn_id      = $token;
        $order->pay_date    = ($pay_date==NULL)?Date::unix2mysql():$pay_date;
        if ($product->support_days>0)
            $order->support_date = Date::unix2mysql(Date::mysql2unix($order->pay_date)+($product->support_days*24*60*60)); 
            //Date::unix2mysql(strtotime('+'.$product->support_days.' day'));
        
        $order->status      = Model_Order::STATUS_PAID;

        try {
            $order->save();
            //if saved delete coupon from session and -- number of coupons.
            Model_Coupon::sale(Controller::$coupon);

            //add affiliate commision
            Model_Affiliate::sale($order,$product);
            
            //generate licenses
            $licenses = Model_License::generate($user,$order,$product);

            $license = '';
            //loop all the licenses to an string
            if (count($licenses)>0)
            {
                $license = '\n\n==== '.__('Your Licenses').' ====';
                foreach ($licenses as $l) 
                    $license.='\n'.$l->license;
            }

            //download link
            $download = '';
            if ($product->has_file()==TRUE)
                $download = '\n\n==== '.__('Download').' ====\n'.$user->ql('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order));
            
            //theres an expire? 0 = unlimited
            $expire = '';
            $expire_hours = Core::config('product.download_hours');
            $expire_times = Core::config('product.download_times');
            if ($expire_hours > 0 OR $expire_times > 0)
            {
                if ($expire_hours > 0 AND $expire_times > 0)
                    $expire = sprintf(__('Your download expires in %u hours and can be downloaded %u times.'),$expire_hours,$expire_times);
                elseif ($expire_hours > 0)
                    $expire = sprintf(__('Your download expires in %u hours.'),$expire_hours);
                elseif ( $expire_times > 0)
                    $expire = sprintf(__('Can be downloaded %u times.'),$expire_times);

                $expire = '\n'.$expire;
            }
            
            //param for sale email
            $params = array(
                            '[DATE]'            => $order->pay_date,
                            '[ORDER.ID]'        => $order->id_order,
                            '[USER.NAME]'       => $user->name,
                            '[USER.EMAIL]'      => $user->email,
                            '[PRODUCT.TITLE]'   => $product->title,
                            '[PRODUCT.PRICE]'   => $order->amount.' '.$order->currency,
                            '[PRODUCT.NOTES]'   => $product->email_purchase_notes,
                            '[DOWNLOAD]'        => $download,
                            '[EXPIRE]'          => $expire,
                            '[LICENSE]'         => $license,
                        );
            
            //send email with order details download link and product notes 
            $user->email('new.sale',$params);

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
        }

        return FALSE;

    }

    /**
     * downloads product attached to this order if there's
     * @return [type] [description]
     */
    public function download()
    {
        if ($this->loaded())
        {
            $expire_hours = Core::config('product.download_hours');
            $expire_times = Core::config('product.download_times');

            //theres an expire? 0 = unlimited
            if ($expire_hours > 0 OR $expire_times > 0)
            {
                //last date, can be last updated the product or the day he paid the order
                $last_date = (Date::mysql2unix($this->product->updated) > Date::mysql2unix($this->pay_date))? $this->product->updated : $this->pay_date;

                //getting the downloads query for this order without filtering
                $downloads = $this->downloads->where('created','>=',$last_date);
                
                //verify hours to expire download
                if ($expire_hours > 0 AND  ( (Date::mysql2unix($last_date)+($expire_hours*60*60)) < time() ) )
                {
                    return sprintf(__('Download expired after %u hours'),$expire_hours);
                }
                
                //checking if he exceeded the downloads
                if ($expire_times > 0 AND $downloads->count_all() >= $expire_times)
                    return sprintf(__('You reached the limit of %u downloads'),$expire_times);
            }

            
            if ($this->product->has_file()==TRUE)
            {
                $file = DOCROOT.'data/'.$this->product->file_name;
                
                //create a download
                Model_Download::generate($this->user, $this);

                //how its called the downloaded file
                $file_name = $this->id_order.'-'.$this->product->seotitle.'-'.$this->product->version.strrchr($file, '.');

                Request::$current->response()->send_file($file,$file_name);
            }
        }

        return __('Can not download');
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
        $form->fields['id_coupon']['display_as']      = 'text';
        $form->fields['status']['display_as']       = 'select';
        $form->fields['status']['options']           = array_keys(self::$statuses);
        //$form->fields['id_user']['display_as']   = 'select';
        //$form->fields['id_user']['caption']      = 'email';   

    }

}