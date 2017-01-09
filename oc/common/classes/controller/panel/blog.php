<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Blog extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('title','created','status');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'post';

	/**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $elements = new Model_Post();
        $elements->where('id_forum','IS',NULL);

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

        if ($view === NULL)
            $view = 'oc-panel/pages/blog/index';
        
        $this->render($view, array('elements' => $elements,'pagination'=>$pagination));
    }    


        /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {

        $this->template->title = __('New').' '.__($this->_orm_model);
        
        $form = new FormOrm($this->_orm_model);
            
        // fields array
        foreach ($form->fields as $field_key => $field) 
        {
            $fields[$field_key] = array('name'=>$field['field_name'], 'value'=>$field['value'], 'id'=>$field['field_id'], 'label'=>$field['label']);
        }
        
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->object->description = Kohana::$_POST_ORIG['formorm']['description'];
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Blog post created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete cache').'">'
                    .__('Delete cache').'</a>');
            
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else 
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/pages/blog/create', array('form' => $fields));
    }
    
    
    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');
    
        $form = new FormOrm($this->_orm_model,$this->request->param('id'));
        
         // fields array
         foreach ($form->fields as $field_key => $field) 
         {
             $fields[$field_key] = array('name'=>$field['field_name'], 'value'=>$field['value'], 'id'=>$field['field_id'], 'label'=>$field['label']);
         }

        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->object->description = Kohana::$_POST_ORIG['formorm']['description'];
              
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Blog post updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete cache').'">'
                    .__('Delete cache').'</a>');
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/pages/blog/update', array('form' => $fields));
    }
}
