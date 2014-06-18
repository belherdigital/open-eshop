<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Review extends Auth_Crud {

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('update');

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	//protected $_index_fields = array('id_review','id_order','id_product','id_user','created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'review';


    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Reviews');
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $reviews = new Model_Review();
        //$reviews = $reviews->where('status', '=', Model_Review::STATUS_ACTIVE);

        if (core::get('email')!==NULL)
        {
            $user = new Model_User();
            $user->where('email','=',core::get('email'))->limit(1)->find();
            if ($user->loaded())
                $reviews = $reviews->where('id_user', '=', $user->id_user);
        }


        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $reviews->count_all(),
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $reviews = $reviews->order_by('created','desc')
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        
        $this->render('oc-panel/pages/review/index', array('reviews' => $reviews,'pagination'=>$pagination));
    }    
}
