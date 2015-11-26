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

        //search box
        if (core::get('search')!==NULL)
        {
            $this->template->title.= ' '.core::get('search') ;

            //email seted
            if ($user->has_access('supportadmin') AND Valid::email(core::get('search')))
            {
                $users = new Model_User();
                $users->where('email','=',core::get('search'))->limit(1)->find();
                if ($users->loaded())
                   $tickets->where('id_user','=',$users->id_user);

                $tickets->where('id_ticket_parent', 'IS', NULL);
            }
            //any user search by ticket ID
            elseif(is_numeric(core::get('search')))
                $tickets->where('id_ticket','=',core::get('search'));
            
            //any user we let him search at any of his tickets
            elseif(strlen(core::get('search'))>3)
            {
                $tickets->where_open()
                        ->where('title', 'like', '%'.core::get('search').'%')
                        ->or_where('description', 'like', '%'.core::get('search').'%')
                        ->where_close();
            }
        }
        //filter by id_user
        elseif (is_numeric(core::request('filter__id_user')) AND $user->has_access('supportadmin')) 
        {
            $tickets->where('id_user','=',core::request('filter__id_user'));
        }
        //theres no search so lets limit the filter only to the parent ticket
        else
            $tickets->where('id_ticket_parent', 'IS', NULL);

        //by type filter 
        switch ($this->request->param('id'))
        {
            case 'assigned':
                if ($user->has_access('supportadmin'))
                {
                    $this->template->title.= ' '. __('Assigned Tickets');
                    $tickets->where('id_user_support','=',$user->id_user);
                }
                else 
                    $tickets->where('id_user','=',$user->id_user);
            break;
            
            case 'admin':
                if ($user->has_access('supportadmin')) 
                    $this->template->title.= ' '. __('All Tickets');
                else
                    $tickets->where('id_user','=',$user->id_user);

            break;
            
            default:
            case 'client':
                $this->template->title.= ' '. $user->name;
                $tickets->where('id_user','=',$user->id_user);
            break;
        }       

        //filter by status
        if (is_numeric(core::get('status')))
        {
            $filter_status = core::get('status');
            if (isset(Model_Ticket::$statuses[$filter_status]))
            {
                $this->template->title.= ' '. Model_Ticket::$statuses[$filter_status];
                $tickets->where('status','=',$filter_status);
            }
        }

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $tickets->count_all(),
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
                    'id'         => $this->request->param('id'),
        ));

        $pagination->title($this->template->title);

        $tickets = $tickets->order_by('status','asc')
                        ->order_by('read_date','desc')
                        ->limit($pagination->items_per_page)
                        ->offset($pagination->offset)
                        ->find_all();

        $pagination = $pagination->render(); 

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/pages/support/index',array('title'=>$this->template->title, 
            'tickets'=>$tickets,
            'pagination'=>$pagination));
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
                ->where('support_date','>',Date::unix2mysql())
                ->where('status', '=', Model_Order::STATUS_PAID)
                ->limit(1)->find();
            

            $validation = Validation::factory($this->request->post())

                ->rule('title', 'not_empty')
                ->rule('title', 'min_length', array(':value', 2))
                ->rule('title', 'max_length', array(':value', 145))

                ->rule('description', 'not_empty')
                ->rule('description', 'min_length', array(':value', 50))
                ->rule('description', 'max_length', array(':value', 1000))
    
                ->rule('order', 'not_empty')
                ->rule('order', 'numeric');

            if ($validation->check() AND $order->loaded())
            {
                $ticket = new Model_Ticket();
                $ticket->id_user  = $user->id_user;
                $ticket->id_order = $id_order;
                $ticket->id_product = $order->product->id_product;
                $ticket->title    = core::post('title');
                $ticket->description    = core::post('description');
                $ticket->ip_address   = ip2long(Request::$client_ip);

                $ticket->save();

                //send email to notify_url @todo we should add a config on general to select who gets the emails by default.
                if(core::config('email.new_sale_notify'))
                {
                    Email::send(core::config('email.notify_email'), '', 'New Ticket: '.$ticket->title, 
                        Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket)).'\n\n'.$ticket->description, 
                        core::config('email.notify_email'), '');
                }

                $user->email('new-ticket',array('[TITLE]'=>$ticket->title,
                                              '[URL.QL]'=>$user->ql('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))));

                
                Alert::set(Alert::SUCCESS, __('Ticket created.'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
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
                        ->where('support_date','>',Date::unix2mysql())
                        ->where('status', '=', Model_Order::STATUS_PAID)
                        ->find_all();


        if ($orders->count() == 0)
        {
            Alert::set(Alert::ALERT, __('You do not have any purchase with support active.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
        }

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/pages/support/new',array('orders'=>$orders));
        $content->errors = $errors;
    }


    //if post create a reply ticket
    public function action_ticket()
    {
        $this->template->scripts['footer'] = array('js/oc-panel/ticket.js');
        //after creating the reply we redirect to the ticket view
        $errors = NULL;

        $user = Auth::instance()->get_user();


        $ticket_id = $this->request->param('id',0);

        //getting the parent ticket
        $ticket = new Model_Ticket();

        if (!$user->has_access('supportadmin'))
            $ticket->where('id_user','=',$user->id_user);

        $ticket->where('id_ticket','=',$ticket_id)
            ->where('id_ticket_parent', 'IS', NULL)
            ->limit(1)
            ->find();
        if (!$ticket->loaded())
        {
            Alert::set(Alert::ERROR, __('Not your ticket.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
        }

        //marking it as read if was not assign we assign an agent.
        if ($ticket->status==Model_Ticket::STATUS_CREATED AND $user->has_access('supportadmin') AND !is_numeric($ticket->id_user_support))
        {
            //modify status of parent ticket
            $ticket->id_user_support = $user->id_user;
            $ticket->read_date = Date::unix2mysql();
            $ticket->status = Model_Ticket::STATUS_READ;
            $ticket->save();
        }

        //Change the agent assigned to this ticket
        if (core::post('agent') AND $user->has_access('supportadmin'))
        {
            //modify ticket 
            $ticket->id_user_support = core::post('agent');
            $ticket->status = Model_Ticket::STATUS_CREATED;
            $ticket->save();

            //send notification to agent
            $agent = new Model_User(core::post('agent'));
            $agent->email('assign-agent',array('[TITLE]'=>$ticket->title,
                                                '[DESCRIPTION]'=>$ticket->description,
                                                '[URL.QL]'=>$agent->ql('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))));

            Alert::set(Alert::SUCCESS, __('Agent assigned.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index','id'=>'admin')));
        }

        //create new reply
        if($this->request->post() AND Form::token('reply_ticket',TRUE))
        {

            $validation = Validation::factory($this->request->post())
            ->rule('description', 'not_empty')
            ->rule('description', 'min_length', array(':value', 5))
            ->rule('description', 'max_length', array(':value', 1000));

            if ($validation->check())
            {
                //creates the answer ticket
                $ticketr = new Model_Ticket();
                $ticketr->id_user           = $user->id_user;
                $ticketr->id_order          = $ticket->id_order;
                $ticketr->id_ticket_parent  = $ticket->id_ticket;
                $ticketr->description       = core::post('description');
                $ticketr->ip_address        = ip2long(Request::$client_ip);
                $ticketr->save();
                unset($_POST['description']);

                //modify status of parent ticket
                $ticket->status = Model_Ticket::STATUS_CREATED;
                $ticket->save();

                //an admin answer so we send email to owner of ticket
                if ($user->has_access('supportadmin'))
                {
                    $ticket->id_user_support = $user->id_user;
                    $ticket->read_date = Date::unix2mysql();
                    $ticket->status = Model_Ticket::STATUS_HOLD;
                    $ticket->save();

                    //send email to creator of the ticket
                    $ticket->user->email('new-reply',array('[TITLE]'=>$ticket->title,
                                                    '[DESCRIPTION]'=> $user->signature,
                                                    '[URL.QL]'=>$ticket->user->ql('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket)))
                                            );

                }
                //client answers so sending email to agent assigned to this ticket.
                elseif(is_numeric($ticket->id_user_support))
                {
                    //send notification to agent
                    $agent = new Model_User($ticket->id_user_support);
                    $agent->email('new-reply',array('[TITLE]'=>$ticket->title,
                                                    '[DESCRIPTION]'=>$ticketr->description,
                                                    '[URL.QL]'=>$agent->ql('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket)))
                                            );

                }
                //client answers so send email to notify_url /admin since we dont have the agent
                elseif(core::config('email.new_sale_notify'))
                {

                    Email::content(core::config('email.notify_email'), NULL, NULL,NULL, 'new-reply', array('[TITLE]'=>$ticket->title,
                                                    '[DESCRIPTION]'=>$ticketr->description,
                                                    '[URL.QL]'=>Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket)))
                                                );
                   
                }
                //set empty since they already replied
                Request::current()->post('description','');
                Alert::set(Alert::SUCCESS, __('Reply created.'));
            }
            else
            {
                $errors = $validation->errors('ad');
            }
                        
        }

        //getting all the ticket replies
        $replies = new Model_Ticket();
        $replies = $replies->where('id_ticket_parent','=',$ticket->id_ticket)
                    ->order_by('created','asc')
                    ->find_all();
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Ticket')));
        $this->template->title   = $ticket->title.' - '.__('Ticket');

        //loading agents/admins 
        $users = NULL;
        if ($user->has_access('supportadmin'))
        {
            //getting the roles that have access to the supportadmin since are the agents ;)
            $support_roles = array(Model_Role::ROLE_ADMIN);
            $access = new Model_Access();
            $access = $access->where('access','=','supportadmin.*')->find_all();
            foreach ($access as $a) 
                $support_roles[] = $a->id_role;

            //getting agents ;)
            $users_db = DB::select('u.id_user')->select('u.name')
                ->from(array('users', 'u'))
                ->where('id_role','in',$support_roles)
                ->as_object()
                ->execute();
            foreach ($users_db as $key => $value) 
                $users[$value->id_user] = $value->name;
        }
        
        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/pages/support/ticket',array('replies'=>$replies,'ticket'=>$ticket, 'users'=>$users));
        $content->errors = $errors;

    }



    //ticket conversation closed
    public function action_close()
    {
        $user = Auth::instance()->get_user();

        $ticket_id = $this->request->param('id',0);

        //getting the parent ticket
        $ticket = new Model_Ticket();

        //only supportadmin can close any ticket
        if (!$user->has_access('supportadmin'))
            $ticket->where('id_user','=',$user->id_user);

        $ticket->where('id_ticket','=',$ticket_id)
            ->where('id_ticket_parent', 'IS', NULL)
            ->limit(1)
            ->find();
        if (!$ticket->loaded())
        {
            Alert::set(Alert::ERROR, __('Not your ticket.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
        }
        else
        {
            //close ticket
            $ticket->status = Model_Ticket::STATUS_CLOSED;
            $ticket->save();

            Alert::set(Alert::SUCCESS, __('Ticket closed.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
        }
        
    }

    /**
     * closes massivley tickets that are older than 1 month and are in status hold
     * @return void 
     */
    public function action_massclose()
    {
        $user = Auth::instance()->get_user();

        //admin can
        if ($user->has_access('supportadmin'))
        {
            $query = DB::update('tickets')
                        ->set(array('status' => Model_Ticket::STATUS_CLOSED))
                        ->where('read_date', '<=', Date::unix2mysql(strtotime('-1 month')))
                        ->where('status','=',Model_Ticket::STATUS_HOLD);
            //d(Debug::vars((string) $query));

            try{
                $query->execute();
            }catch (exception $e) {
                Alert::set(Alert::SUCCESS, __('Error closing tickets.'));
            }

            Alert::set(Alert::SUCCESS, __('Tickets closed.'));
            
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'support','action'=>'index')));
        
    }
            



}
