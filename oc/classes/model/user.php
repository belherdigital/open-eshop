<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User model
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_User extends Model_OC_User {


    /**
     * creates a user from email if exists doesn't...
     * @param  string $email 
     * @param  string $name  
     * @param  string $password
     * @return Model_User        
     */
    public static function create_email($email,$name=NULL,$password=NULL)
    {
        $user = new self();
        $user->where('email','=',$email)->limit(1)->find();

        if (!$user->loaded())
        {
            if ($password === NULL)
                $password       = Text::random('alnum', 8);

            $user->email        = $email;
            $user->name         = ($name===NULL OR !isset($name))? substr($email, 0, strpos($email, '@')):$name;
            $user->status       = self::STATUS_ACTIVE;
            $user->id_role      = Model_Role::ROLE_USER;;
            $user->seoname      = $user->gen_seo_title($user->name);
            $user->password     = $password;
            $user->subscriber   = 1;
            $user->last_ip      = ip2long(Request::$client_ip);
            $user->country      = euvat::country_code();//geo info EU

            try
            {
                $user->save();
                //send welcome email
                $url = $user->ql('oc-panel',array('controller' => 'profile', 
                                                  'action'     => 'edit'),TRUE);

                $user->email('auth-register',array('[USER.PWD]'=>$password,
                                                    '[URL.QL]'=>$url)
                                            );
            }
            catch (ORM_Validation_Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }

        return $user;
    }

        /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @throws Kohana_Exception
     * @return ORM
     */
    public function delete()
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));

        //we can not delete if has products!
        $products = new Model_Product();
        $products = $products->where('id_user','=',$this->id_user)->count_all();
        
        if ($products>0)
            throw new Kohana_Exception('Cannot delete :model model because it has products.', array(':model' => $this->_object_name));

        //remove image
        $this->delete_image();

        //delete licenses
        DB::delete('licenses')->where('id_user', '=',$this->id_user)->execute();

        //delete downloads
        DB::delete('downloads')->where('id_user', '=',$this->id_user)->execute();

        //delete tickets
        DB::delete('tickets')->where('id_user', '=',$this->id_user)->execute();
        
        //delete affiliates
        DB::delete('affiliates')->where('id_user', '=',$this->id_user)->execute();

        //delete reviews
        DB::delete('reviews')->where('id_user', '=',$this->id_user)->execute();

        //delete orders
        DB::delete('orders')->where('id_user', '=',$this->id_user)->execute();

        //remove visits ads
        DB::update('visits')->set(array('id_user' => NULL))->where('id_user', '=',$this->id_user)->execute();

        //delete posts
        DB::delete('posts')->where('id_user', '=',$this->id_user)->execute();

        //unsusbcribe from elasticemail
        if ( Core::config('email.elastic_listname')!='' )
            ElasticEmail::unsubscribe(Core::config('email.elastic_listname'),$this->email);
        
        parent::delete();
    }

} // END Model_User