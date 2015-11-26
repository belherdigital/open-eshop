<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Menu extends Auth_Controller {
	



    /**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_index($view = NULL)
    {
        //template header
        $this->template->title  = __('Menu');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Menu')));
        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/menu.js';

        //find all, for populating form select fields 
        $categories       = Model_Category::get_as_array();
        $order_categories = Model_Category::get_multidimensional();

        // d($categories);
        $this->template->content = View::factory('oc-panel/pages/menu/index',array('menu' => Menu::get(), 
                                                                             'categories'=>$categories,
                                                                             'order_categories'=>$order_categories));
    }


    /**
     * saves the category in a specific order and change the parent
     * @return void 
     */
    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        if (Menu::change_order(Core::get('order')))
        {
            Core::delete_cache();
            $this->template->content = __('Saved');
        }
        else
            $this->template->content = __('Error');

    }



    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;

        Menu::delete($this->request->param('id'));
        
        Alert::set(Alert::SUCCESS, __('Menu deleted'));
        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'menu','action'=>'index')));  

    }

    public function action_new()
    {
        $this->auto_render = FALSE;

        if (Menu::add(Core::post('title'),Core::post('url'),Core::post('target'),Core::post('icon')))
            Alert::set(Alert::SUCCESS, __('Menu created'));
        else
            Alert::set(Alert::ERROR, __('Menu not created'));

        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'menu','action'=>'index')));  
    }

    public function action_update()
    {
        $name   = $this->request->param('id');
        $menu_data  = Menu::get_item($name);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Menu').' '.$menu_data['title']));
        $this->template->title = __('Edit Menu');

        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/menu.js';

        //find all, for populating form select fields 
        $categories         = Model_Category::get_as_array();  
        $order_categories   = Model_Category::get_multidimensional();

        if ($_POST)
        {
			if (Menu::update($name, Core::post('title'),Core::post('url'),Core::post('target'),Core::post('icon')))
				Alert::set(Alert::SUCCESS, __('Menu updated'));
			else
				Alert::set(Alert::ERROR, __('Menu not updated'));

	        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'menu','action'=>'index')));  
        }

        // d($categories);
        $this->template->content = View::factory('oc-panel/pages/menu/update',array('menu_data'=>$menu_data,'name'=>$name, 
                                                                             'categories'=>$categories,
                                                                             'order_categories'=>$order_categories));
    }

}
