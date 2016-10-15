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

    protected $_has_one = array(
        'affiliate' => array(
                'model'       => 'affiliate',
                'foreign_key' => 'id_order',
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
     * [new_order description]
     * @param  Model_User    $user    [description]
     * @param  Model_Product $product [description]
     * @param  boolean       check_match_product, if set to false will update the order with the product if different
     * @return [type]                 [description]
     */
    public static function new_order(Model_User $user, Model_Product $product, $match_product = TRUE)
    {
        
        $order = new Model_Order();

        if ($user->loaded() AND $product->loaded())
        {
            //get if theres an unpaid order for this user we wwill use it..
            $order  ->where('id_user',  '=', $user->id_user)
                    ->where('status',   '=', Model_Order::STATUS_CREATED);

            //also check that matches the product for the order
            if ($match_product === TRUE)
            {
                $order->where('id_product', '=', $product->id_product)
                        ->where('amount',   '=', $product->final_price())
                        ->where('currency', '=', $product->currency);
            }

            $order->limit(1)->find();


            //order didnt exist so lets create it.
            if ($order->loaded()===FALSE)
            {
                //create order      
                $order = new Model_Order();
                $order->id_user       = $user->id_user;
            }

            // no matter what happens if product is different save! this will also save the order if its new ;) 
            if ( $order->id_product!=$product->id_product )
            {
                $order->ip_address    = ip2long(Request::$client_ip);
                $order->id_product    = $product->id_product;
                $order->currency      = $product->currency;
                
                //add coupon ID and discount
                if (Model_Coupon::current()->loaded())
                    $order->id_coupon = Model_Coupon::current()->id_coupon;

                $order->amount        = $product->final_price();
                $order->VAT           = euvat::vat_percentage();
                $order->VAT_number    = $user->VAT_number;
                $order->country       = $user->country;
                $order->city          = $user->city;
                $order->postal_code   = $user->postal_code;
                $order->address       = $user->address;

                try {
                    $order->save();
                } 
                catch (Exception $e){
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            } 

            
        }
        
        return $order;
    }

    /**
     * confirm payment for order
     *
     * @param string    $id_order [unique indentifier of order]
     * @param string    $txn_id id of the transaction depending on provider
     */
    public function confirm_payment($paymethod = 'paypal', $txn_id = NULL, $pay_date = NULL , $amount = NULL, $currency = NULL , $fee = NULL)
    { 
        
        // update orders
        if($this->loaded() AND $this->status != self::STATUS_PAID)
        {
            $product = $this->product;
            $user    = $this->user;

            $this->status    = self::STATUS_PAID;
            $this->pay_date  = ($pay_date===NULL)?Date::unix2mysql():$pay_date;
            $this->paymethod = $paymethod;
            $this->txn_id    = $txn_id;

            if ($product->support_days>0)
                $this->support_date = Date::unix2mysql(Date::mysql2unix($this->pay_date)+($product->support_days*24*60*60)); 

            if ($amount!==NULL)
                $this->amount = $amount;

            if ($currency!==NULL)
                $this->currency = $currency;

            //get gateway fee
            $this->gateway_fee = ($fee!==NULL)?$fee:0;
           
            //get VAT paid
            if ($this->VAT > 0)
                $this->VAT_amount = $this->amount - (100*$this->amount)/(100+$this->VAT);
            else
                $this->VAT_amount = 0;

            //calculate net amount
            $this->amount_net = $this->amount - $this->gateway_fee - $this->VAT_amount;

            try {
                $this->save();
            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());  
            }

            //if saved delete coupon from session and -- number of coupons.
            Model_Coupon::sale($this->coupon);

            //add affiliate commision
            Model_Affiliate::sale($this,$product);
            
            //generate licenses
            $licenses = Model_License::generate($user,$this,$product);

            $license = '';
            //loop all the licenses to an string
            if (count($licenses)>0)
            {
                $license = '<br><br>==== '.__('Your Licenses').' ====';
                foreach ($licenses as $l) 
                    $license.='<br>'.$l->license;
            }

            //download link
            $download = '';
            if ($product->has_file()==TRUE)
            {
                $dwnl_link = $user->ql('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$this->id_order));
                $download = '<br><br>==== '.__('Download').' ====<br><a href="'.$dwnl_link.'">'.$dwnl_link.'</a>';
            }
                
            
            //theres an expire? 0 = unlimited
            $expire = '';
            $expire_hours = Core::config('product.download_hours');
            $expire_times = Core::config('product.download_times');
            if ( ($expire_hours > 0 OR $expire_times > 0) AND $product->has_file()==TRUE)
            {
                if ($expire_hours > 0 AND $expire_times > 0)
                    $expire = sprintf(__('Your download expires in %u hours and can be downloaded %u times.'),$expire_hours,$expire_times);
                elseif ($expire_hours > 0)
                    $expire = sprintf(__('Your download expires in %u hours.'),$expire_hours);
                elseif ( $expire_times > 0)
                    $expire = sprintf(__('Can be downloaded %u times.'),$expire_times);

                $expire = '<br>'.$expire;
            }
            
            //param for sale email
            $params = array(
                            '[DATE]'            => $this->pay_date,
                            '[ORDER.ID]'        => $this->id_order,
                            '[USER.NAME]'       => $user->name,
                            '[USER.EMAIL]'      => $user->email,
                            '[PRODUCT.TITLE]'   => $product->title,
                            '[PRODUCT.PRICE]'   => i18n::format_currency($this->amount,$this->currency),
                            '[PRODUCT.NOTES]'   => Text::bb2html($product->email_purchase_notes,TRUE,FALSE,FALSE),
                            '[DOWNLOAD]'        => $download,
                            '[EXPIRE]'          => $expire,
                            '[LICENSE]'         => $license,
                        );
            
            //send email with order details download link and product notes 
            $user->email('new-sale',$params);

            //notify to seller
            if(core::config('email.new_sale_notify'))
            {
                Email::send(core::config('email.notify_email'), '', 'New Sale! '.$product->title, 'New Sale! '.$product->title, core::config('email.notify_email'), '');
            }


            return TRUE;
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

                //Request::$current->response()->send_file($file,$file_name);
                Response::factory()->send_file($file,$file_name);
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


    /**
     * verifies pricing in an existing order
     * @return void
     */
    public function check_pricing()
    {
        //original coupon so we dont lose it while we do operations
        $orig_coupon = $this->id_coupon;

        //remove the coupon forced by get/post
        if(core::request('coupon_delete') != NULL)
            $this->id_coupon = NULL;
        //maybe changed the coupon? from the form
        elseif ($this->product->valid_coupon() AND $this->id_coupon != Model_Coupon::current()->id_coupon )              
            $this->id_coupon = Model_Coupon::current()->id_coupon;
        //not valid coupon anymore, this can happen if they add a coupon now but they pay days later.
        elseif($this->coupon->loaded() AND (
                                            Date::mysql2unix($this->coupon->valid_date) < time()  OR
                                            $this->coupon->status == 0 OR
                                            $this->coupon->number_coupons == 0 
                                            ))
        {
            Alert::set(Alert::INFO, __('Coupon not valid, expired or already used.'));
            $this->coupon->clear();
            $this->id_coupon = NULL;
        }
        
        $user = $this->user;

        //recalculate price since it change the coupon or user info
        if ($orig_coupon != $this->id_coupon OR
            $this->country!=$user->country OR
            $this->city!=$user->city OR
            $this->VAT_number!=$user->VAT_number OR
            $this->postal_code!=$user->postal_code OR
            $this->address!=$user->address)
        {
            
            //set variables just in case...
            $this->amount        = $this->product->final_price();
            $this->VAT           = euvat::vat_percentage();
            $this->VAT_number    = $user->VAT_number;
            $this->country       = $user->country;
            $this->city          = $user->city;
            $this->postal_code   = $user->postal_code;
            $this->address       = $user->address;

            try {
                $this->save();
            } 
            catch (Exception $e){
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }

    }


    /**
     * renders a modal with alternative paymethod instructions
     * @return string 
     */
    public function alternative_pay_button()
    {
        if($this->loaded())
        {
            if (core::config('payment.alternative')!='' )
            {
                $content = Model_Content::get_by_title(core::config('payment.alternative'));
                return View::factory('pages/alternative_payment',array('content'=>$content))->render();
            }
        }
    
        return FALSE;
    }

    /**
     * unpaid orders 2 days ago reminder
     * @param integer $days, how many days after created
     * @return void
     */
    public static function cron_unpaid($days = 2)
    {
        //getting orders not paid from 2 days ago
        $orders = new Model_Order();
        $orders = $orders->where('status','=',Model_Order::STATUS_CREATED)
                            ->where(DB::expr('DATE( created)'),'=', Date::format('-'.$days.' days','Y-m-d'))
                            ->find_all();

        foreach ($orders as $order) 
        {
            $url_checkout = $order->user->ql('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order));

            $order->user->email('new-order', array(  '[ORDER.ID]'    => $order->id_order,
                                                     '[ORDER.DESC]'  => $order->description,
                                                     '[URL.CHECKOUT]'=> $url_checkout));
        }
    }


    /**
     * verify if a transaction is fraudulent
     * @return boolean                    
     */
    public function is_fraud()
    {
        //only production and api set
        if ($this->loaded() AND core::config('payment.fraudlabspro')!='')
        {
            //get the country
            $country_code = euvat::country_code();

            // Include FraudLabs Pro library
            require Kohana::find_file('vendor/', 'FraudLabsPro.class');

            $fraud = new FraudLabsPro(core::config('payment.fraudlabspro'));

            try {
                // Check this transaction for possible fraud. FraudLabs Pro support comprehensive validation check,
                // and for this example, we only perform the IP address, BIN and billing country validation.
                // For complete validation, please check our developer page at http://www.fraudlabspro.com/developer
                $fraud_result = $fraud->check(array(
                    'ipAddress'         => Request::$client_ip,
                    'billingCountry'    => $country_code,
                    'quantity'          => 1,
                    'amount'            => $this->amount,
                    'currency'          => $this->currency,
                    'emailAddress'      => $this->user->email,
                    'paymentMode'       => 'others',
                    'flpChecksum'       => Core::cookie('flp_checksum',session_id()),
                ));

                $fraud_result_status = $fraud_result->fraudlabspro_status;
             
            } 
            catch (Exception $e) {
                $fraud_result_status = 'DECLINED';
            }

            // This transaction is legitimate, let's submit to Stripe
            if($fraud_result_status == 'APPROVE')
            {
                return FALSE;
            }
            //not approved!! fraud! save log
            else
            {
                Kohana::$log->add(Log::ERROR, 'Fraud detected id_order:'.$this->id_order);                
                return TRUE;
            }
            
        }
        
        //by default we say is not fraud
        return FALSE;

    }
}