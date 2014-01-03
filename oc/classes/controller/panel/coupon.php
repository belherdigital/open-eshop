<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Coupon extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('name','number_coupons','discount_amount','discount_percentage','valid_date','created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'coupon';


    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Coupons');
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $elements = new Model_Coupon();
        
        if (core::get('name')!==NULL)
            $elements = $elements->where('name', '=', core::get('name'));


        $pagination = Pagination::factory(array(
                    'view'           => 'pagination',
                    'total_items'    => $elements->count_all(),
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

        
        $this->render('oc-panel/pages/coupon/index', array('elements' => $elements,'pagination'=>$pagination));
    }  

}
