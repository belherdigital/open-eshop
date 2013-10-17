<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Support extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Support'))->set_url(Route::url('oc-panel',array('controller'  => 'support'))));

    }

    public function action_index()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Tickets')));
        $this->template->title   = __('Support');

        $user = Auth::instance()->get_user();

        $tickets = new Model_Ticket();

        $tickets = $tickets->where('id_user','=',$user->id_user)
                        ->where('id_ticket_parent', 'IS', NULL)
                        ->order_by('created','desc')
                        ->find_all();

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/pages/support/index',array('tickets'=>$tickets));
    }


    //creates new parent ticket
    public function action_new()
    {
        $errors = NULL;

        $user = Auth::instance()->get_user();

        //create new ticket
        if($_POST)
        {
            //if post save
            $id_order = core::post('order');

            //check if that order still have support...no cheating!! :D
            $order = new Model_Order();

            $order->where('id_order','=',$id_order)
                ->where('id_user','=',$user->id_user)
                ->where('support_date','>',DB::expr('NOW()'))
                ->where('status', '=', Model_Order::STATUS_PAID)
                ->limit(1)->find();
            

            $validation = Validation::factory($this->request->post())

                ->rule('title', 'not_empty')
                ->rule('title', 'min_length', array(':value', 2))
                ->rule('title', 'max_length', array(':value', 145))

                ->rule('description', 'not_empty')
                ->rule('desc', 'min_length', array(':value', 50))
    
                ->rule('order', 'not_empty')
                ->rule('order', 'numeric');

            if ($validation->check() AND $order->loaded())
            {
                $ticket = new Model_Ticket();
                $ticket->id_user  = $user->id_user;
                $ticket->id_order = $id_order;
                $ticket->title    = core::post('title');
                $ticket->description    = core::post('description');

                $ticket->save();

                //send email to notify_url
                if(core::config('email.new_sale_notify'))
                {
                    Email::send(core::config('email.notify_email'), '', 'New Ticket! '.$ticket->title, 'New Ticket! '.$ticket->title, core::config('email.notify_email'), '');
                }
                
                Alert::set(Alert::SUCCESS, __('Ticket created.'));
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
            }
            else
            {
                $errors = $validation->errors('ad');
            }
        }
        
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Ticket')));
        $this->template->title   = __('New Ticket');
       

        //get orders with support
        $orders = new Model_Order();

        $orders = $orders->where('id_user','=',$user->id_user)
                        ->where('support_date','>',DB::expr('NOW()'))
                        ->where('status', '=', Model_Order::STATUS_PAID)
                        ->find_all();


        if ($orders->count() == 0)
        {
            Alert::set(Alert::ERROR, __('You do not have any purchase with support active.'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
        }

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/pages/support/new',array('orders'=>$orders));
        $content->errors = $errors;
    }


    //if post create a reply ticket
    public function action_reply()
    {
        //after creating the reply we redirect to the ticket view
    }


    //ticket conversation display
    public function action_ticket()
    {
        //reads ticket if its a ticket not an answer, and we load the entire conversation
        
        //mark all tickets as read if admin
        //if we answer admin, status on hold
    }


    //ticket conversation display
    public function action_close()
    {
        //reads ticket if its a ticket not an answer, and we load the entire conversation
        
    }

            



}
