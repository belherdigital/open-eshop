<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Affiliate extends Auth_Crud {

    
	
	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('amount','created');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'affiliate';
	

	//at affiliates admin a payment action, listing all the affiliates with payments to do and a button to pay, will generate an order and after order created a pay button with paypal

    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Affiliates Commissions');
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $commissions = new Model_Affiliate();

        //filter by email
        if (core::get('email')!==NULL)
        {
            $user = new Model_User();
            $user->where('email','=',core::get('email'))->limit(1)->find();
            if ($user->loaded())
                $commissions = $commissions->where('id_user', '=', $user->id_user);
        }


        $pagination = Pagination::factory(array(
                    'view'           => 'pagination',
                    'total_items'    => $commissions->count_all(),
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $commissions = $commissions->order_by('created','desc')
                                ->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all();

        $pagination = $pagination->render();

        
        $this->render('oc-panel/pages/affiliate/index', array('commissions' => $commissions,'pagination'=>$pagination));
    }    

    /**
     *
     * view affiliates and payments
     */
    public function action_pay()
    {
        $this->template->title = __('Affiliates Payments');
        
        $query = DB::select(DB::expr('SUM(amount) total'))
                        ->select('id_user')
                        ->from('affiliates')
                        ->where('date_to_pay','>',Date::unix2mysql())
                        ->where('status','=',Model_Affiliate::STATUS_CREATED)
                        ->group_by('id_user')
                        ->execute();
        $users_to_pay = $query->as_array('id_user');

        $total_to_pay = 0;
        foreach ($users_to_pay as $key => $value) 
            $total_to_pay+= $value['total'];
        

        $users = new Model_User();
        $users = $users
                ->where('id_user','in',array_keys($users_to_pay))
                ->where('status','=',Model_User::STATUS_ACTIVE)
                ->find_all();
        
        $this->render('oc-panel/pages/affiliate/pay', array('users' => $users,'total_to_pay'=>$total_to_pay,'users_to_pay'=>$users_to_pay));
    }    
}
