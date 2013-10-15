<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controllers user access
 *
 * @author      Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Subscribe extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'subscribers';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_subscribe';


    public function form_setup($form)
    {
       
    }

    public function exclude_fields()
    {
    
    }

    /**
     * subscriber mode
     * if subscriber mode is on, publish new renders subscribe.html instead of publish-new.html
     */
    const SUBSCRIBER_OFF  = 0; // subscriber mode of
    const SUBSCRIBER_DEFAULT = 1; // subscriber mode for default template on
    const SUBSCRIBER_MOBILE = 2; // subscriber mode for mobile template on
    const SUBSCRIBER_DEFAULT_MOBILE = 3; // subscriber mode for default template and mobile template on

    /**
     * Function for saving emails to subscribers
     */
    public static function find_subscribers($data, $price, $seotitle, $email)
    {
      // locations are optional , get wiget settings for locations and categories 
      $jsonObj = json_decode(core::config('widget.Widget_Subscribers_1373877069'), true);
      
      $subscribers = new Model_Subscribe();
      $category = new Model_Category($data['cat']);

      if($category->loaded())
      {
        if($category->id_category_parent !== 1)
          $cat_parent = $category->id_category_parent;
      }

      //only min/max price is required in widget settings
      if($price !== '0')
        $subscribers->where('min_price', '<=', $price)
                    ->where('max_price', '>=', $price);
      else
        $subscribers->where('min_price', '<=', 0)
                    ->where('max_price', '>=', 0);

      //location is set     
      if($data['loc'] != NULL AND $jsonObj['data']['locations'] !== '0')
       $subscribers =  $subscribers->where('id_location', '=', $data['loc']);

      //category is set
      if($jsonObj['data']['categories'] !== '0')
        $subscribers =  $subscribers->where('id_category', 'IN', array($data['cat'], $cat_parent));

      $subscribers = $subscribers->find_all();

      $subscribers_id = array(); // array to be filled with user emails
      foreach ($subscribers as $subs) 
      {
        // do not repeat same users.
        if(!in_array($subs->id_user, $subscribers_id))
          $subscribers_id[] = $subs->id_user;
      }
      
      // query for getting users, transform it to array and pass to email function 
      if(count($subscribers_id) > 0)
      {  

        $query = DB::select('email')->select('name')
                    ->from('users')
                    ->where('id_user', 'IN', $subscribers_id)
                    ->where('status','=',Model_User::STATUS_ACTIVE)
                    ->execute();

        $users = $query->as_array();

        $user = new Model_User();
        $user = $user->where('email', '=', $email)
                     ->where('status','=',Model_User::STATUS_ACTIVE)->limit(1)->find();

        // Send mails like in newsletter, to multiple users simultaneously @TODO NOT YET READY 
        if (count($users)>0)
        {
            $url_ad = $user->ql('ad', array('category'=>$data['cat'],
                                            'seotitle'=>$seotitle), TRUE); 

            if ( !Email::send($users,'',"Advertisement is created on ".core::config('general.site_name')."!",
                                        "Hello, You may be interested in this one: \n\n ".$data['title']."! \n\n
                                        You can visit this link to see advertisement ".$url_ad,
                                        "no-reply ".core::config('general.site_name'), 
                                        core::config('email.notify_email') ) )
                Alert::set(Alert::ERROR,__('Error on mail delivery, not sent'));
        }
      }

    }


 protected $_table_columns =     
array (
  'id_subscribe' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_ad',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'id_user' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_user',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_category' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_location' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'min_price' => 
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'price',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'numeric_scale' => '3',
    'numeric_precision' => '14',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'max_price' => 
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'price',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'numeric_scale' => '3',
    'numeric_precision' => '14',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' => 
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

}