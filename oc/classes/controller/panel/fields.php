<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Fields extends Auth_Controller {

    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields'))->set_url(Route::url('oc-panel',array('controller'  => 'fields'))));

    }

	public function action_index()
	{
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields for Advertisements')));
		$this->template->title = __('Custom Fields');

        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/fields.js';

        //retrieve fields

		$this->template->content = View::factory('oc-panel/pages/fields/index',array('fields'=>Model_Field::get_all()));
	}
    

    public function action_new()
    {
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New')));
        $this->template->title = __('New Custom Field for Advertisement');


        if ($_POST)
        {
            $name   = URL::title(Core::post('name'));

            $field = new Model_Field();

            try {

                $options = array(
                                'label'     => Core::post('label'),
                                'required'  => (Core::post('required')=='on')?TRUE:FALSE,
                                'searchable'=> (Core::post('searchable')=='on')?TRUE:FALSE,
                                );

                if ($field->create($name,Core::post('type'),Core::post('values'),$options))
                {
                    Cache::instance()->delete_all();
                    Theme::delete_minified();

                    Alert::set(Alert::SUCCESS,__('Field created '.$name));
                    Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));  
                }
                else
                    Alert::set(Alert::ERROR,__('Field already exists '.$name));

                

            } catch (Exception $e) {
                throw new HTTP_Exception_500();     
            }
        }

        $this->template->content = View::factory('oc-panel/pages/fields/new',array());
    }

    public function action_update()
    {
        $name   = $this->request->param('id');
        $field  = new Model_Field();
        $field_data  = $field->get($name);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit').' '.$name));
        $this->template->title = __('Edit Custom Field for Advertisement');


        if ($_POST)
        {

            try {

                $options = array(
                                'label'     => Core::post('label'),
                                'required'  => (Core::post('required')=='on')?TRUE:FALSE,
                                'searchable'=> (Core::post('searchable')=='on')?TRUE:FALSE,
                                );

                if ($field->update($name,Core::post('values'),$options))
                {
                    Cache::instance()->delete_all();
                    Theme::delete_minified();

                    Alert::set(Alert::SUCCESS,__('Field edited '.$name));
                    Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));  
                }
                else
                    Alert::set(Alert::ERROR,__('Field cant be edited'.$name));

                

            } catch (Exception $e) {
                throw new HTTP_Exception_500();     
            }
        }

        $this->template->content = View::factory('oc-panel/pages/fields/update',array('field_data'=>$field_data,'name'=>$name));
    }


    public function action_delete()
    {
        //get name of the param, get the name of the custom fields, deletes from config array and alters table
        $this->auto_render = FALSE;
        $name   = $this->request->param('id');
        $field  = new Model_Field();

        try {

                if ($field->delete($name))
                {
                    Cache::instance()->delete_all();
                    Theme::delete_minified();
                    Alert::set(Alert::SUCCESS,__('Field deleted '.$name));
                }
                else
                    Alert::set(Alert::ERROR,__('Field does not exists '.$name));

                $this->request->redirect(Route::url('oc-panel', array('controller'=>'fields', 'action'=>'index')));

        } catch (Exception $e) {
            //throw 500
            throw new HTTP_Exception_500();     
        }
        
        
    }

    /**
     * used for the ajax request to reorder the fields
     * @return string 
     */
    public function action_saveorder()
    {
        $field  = new Model_Field();

        $this->auto_render = FALSE;
        $this->template = View::factory('js');


        if ($field->change_order(Core::get('order')))

            $this->template->content = __('Saved');
        else
            $this->template->content = __('Error');
    }
	

}
