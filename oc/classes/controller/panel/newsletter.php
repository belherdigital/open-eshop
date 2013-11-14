<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Newsletter extends Auth_Controller {



    public function action_index()
    {

        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Newsletter')));  
        $this->template->title = __('Newsletter');

        $user = new Model_User();
        $user->where('status','=',Model_User::STATUS_ACTIVE);
        $user = $user->count_all();

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
                    Alert::set(Alert::SUCCESS,__('Email sent to all the users'));
            }
            else
            {
                Alert::set(Alert::ERROR,__('Mail not sent'));
            }

            
        }

        



        $this->template->content = View::factory('oc-panel/pages/newsletter',array('count'=>$user));

    }




}//end of controller