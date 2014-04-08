<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Newsletter extends Auth_Controller {



    public function action_index()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Newsletter')));  
        $this->template->title = __('Newsletter');

        //count all users
        $user = new Model_User();
        $user->where('status','=',Model_User::STATUS_ACTIVE);
        $count_all_users = $user->count_all();

        //count support expired
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->from('orders')
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->where('support_date','<',DB::expr('NOW()'))
                        ->execute();

        $count_support_expired = $query->as_array();
        $count_support_expired = $count_support_expired[0]['count'];

        
        //count license expired
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where('valid_date','IS NOT',NULL)
                        ->where('valid_date','<',DB::expr('NOW()'))
                        ->execute();

        $count_license_expired = $query->as_array();
        $count_license_expired = $count_license_expired[0]['count'];
        
        
        //users per product
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select('p.title')
                        ->select('p.id_product')
                        ->from(array('products','p'))
                        ->join(array('orders','o'))
                        ->using('id_product')
                        ->where('o.status','=',Model_Order::STATUS_PAID)
                        ->group_by('p.id_product')
                        ->execute();
        $products = $query->as_array();

        if($this->request->post())
        {
            $query = DB::select('email')->select('name')
                        ->from('users')
                        ->where('status','=',Model_User::STATUS_ACTIVE)
                        ->execute();

            $users = $query->as_array();

            if (count($users)>0 OR Core::post('subject')!=NULL)
            {
                if ( !Email::send($users,'',Core::post('subject'),Core::post('description'),Core::post('from'), Core::post('from_email') ) )
                    Alert::set(Alert::ERROR,__('Error on mail delivery, not sent'));
                else 
                    Alert::set(Alert::SUCCESS,__('Email sent'));
            }
            else
            {
                Alert::set(Alert::ERROR,__('Mail not sent'));
            }

        }

        $this->template->content = View::factory('oc-panel/pages/newsletter',array( 'count_all_users'       => $count_all_users,
                                                                                    'count_support_expired' => $count_support_expired,
                                                                                    'count_license_expired' => $count_license_expired,
                                                                                    'products' => $products
                                                                                    )
                                                                                );

    }




}//end of controller