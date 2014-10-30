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
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/coupon.js';
        
        $elements = new Model_Coupon();
        
        if (core::get('name')!==NULL)
            $elements = $elements->where('name', '=', core::get('name'));


        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
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

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {

        $this->template->title = __('New').' '.__($this->_orm_model);
        
        $this->template->styles             = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer']  = array(
                                                    '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/coupon.js'
                                                );

        $form = new FormOrm($this->_orm_model);

        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');

                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else 
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        return $this->render('oc-panel/crud/create', array('form' => $form));
    }
    
    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');

        $this->template->styles             = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer']  = array(
                                                    '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/coupon.js'
                                                );

        $form = new FormOrm($this->_orm_model,$this->request->param('id'));

        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        return $this->render('oc-panel/crud/update', array('form' => $form));
    }

}
