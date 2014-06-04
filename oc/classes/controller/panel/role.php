<?php 

class Controller_Panel_Role extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_role','name');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'role';


    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $id_role = $this->request->param('id');

        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$id_role;
    
        $role = new Model_Role($id_role);
        
        if ($this->request->post() AND $role->loaded())
        {
            //delete all the access
            DB::delete('access')->where('id_role','=',$role->id_role)->execute();
            //set all the access where post = on
            foreach ($_POST as $key => $value) 
            {
                if ($value == 'on')
                {
                   DB::insert('access', array('id_role','access' ))->values(array($role->id_role, str_replace('|', '.', $key)))->execute();
                }
            }

            //saving the role params
            $role->name = core::post('name');
            $role->description = core::post('description');
            $role->save();            

            Alert::set(Alert::SUCCESS, __('Item updated'));
           
            $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
           
        }

        //getting controllers actions
        $controllers = Model_Access::list_controllers();

        //get all the access this user has
        $query = DB::select('access')
                        ->from('access')
                        ->where('id_role','=',$id_role)                        
                        ->execute();

        $access_in_use = array_keys($query->as_array('access'));
    
   // d(in_array('access_index',$access_in_use));
//d($access_in_use);

        return $this->render('oc-panel/pages/role/update', array('role' => $role, 
                                                                'controllers' => $controllers,
                                                                'access_in_use'=>$access_in_use));
    }

	
}
