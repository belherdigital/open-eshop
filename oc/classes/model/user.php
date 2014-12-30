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

} // END Model_User