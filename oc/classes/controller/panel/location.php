<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Location extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_location','name','id_location_parent');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'location';


    /**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_index($view = NULL)
    {
        //template header
        $this->template->title  = __('Locations');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Locations')));
        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/locations.js';

        list($locs,$order)  = Model_Location::get_all();

        $this->template->content = View::factory('oc-panel/pages/locations',array('locs' => $locs,'order'=>$order));
    }


    /**
     * saves the location in a specific order and change the parent
     * @return void 
     */
    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $loc = new Model_Location(core::get('id_location'));

        if ($loc->loaded())
        {
            //saves the current location
            $loc->id_location_parent = core::get('id_location_parent');
            $loc->parent_deep        = core::get('deep');
            

            //saves the categories in the same parent the new orders
            $order = 0;
            foreach (core::get('brothers') as $id_loc) 
            {
                $id_loc = substr($id_loc,3);//removing the li_ to get the integer

                //not the main location so loading and saving
                if ($id_loc!=core::get('id_location'))
                {
                    $c = new Model_Location($id_loc);
                    $c->order = $order;
                    $c->save();
                }
                else
                {
                    //saves the main location
                    $loc->order  = $order;
                    $loc->save();
                }
                $order++;
            }


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

        $location = new Model_Location($this->request->param('id'));

        //update the elements related to that ad
        if ($location->loaded())
        {
            //update all the siblings this location has and set the location parent
            $query = DB::update('locations')
                        ->set(array('id_location_parent' => $location->id_location_parent))
                        ->where('id_location_parent','=',$location->id_location)
                        ->execute();

            //update all the ads this location has and set the location parent
            $query = DB::update('ads')
                        ->set(array('id_location' => $location->id_location_parent))
                        ->where('id_location','=',$location->id_location)
                        ->execute();

            try
            {
                $location->delete();
                $this->template->content = 'OK';
                Alert::set(Alert::SUCCESS, __('Location deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::SUCCESS, __('Location not deleted'));

        
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index')));  

    }
}
