<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Download extends Auth_Crud {

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array();

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_download','id_order','id_user','ip_address','created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'download';


    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $elements = ORM::Factory($this->_orm_model);//->find_all();


        //email search
        if (Valid::email(core::get('email')))
        {
            $users = new Model_User();
            $users->where('email','=',core::get('email'))->limit(1)->find();
            if ($users->loaded())
               $elements->where('id_user','=',$users->id_user);
        }

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $elements->count_all(),
        //'items_per_page' => 10// @todo from config?,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $elements = $elements->order_by('created','desc')
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        
        $this->render('oc-panel/pages/download/index', array('elements' => $elements,'pagination'=>$pagination));
    }    

}
