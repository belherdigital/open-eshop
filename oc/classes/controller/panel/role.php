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
    
        $form = new FormOrm($this->_orm_model,$id_role);
        
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1">'
                    .__('Delete All').'</a>');
                $this->request->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        $controllers = Model_Access::list_controllers();

        

        //count license expired
        $query = DB::select('access')
                        ->from('access')
                        ->where('id_role','=',$id_role)                        
                        ->execute();

        $access_in_use = array_keys($query->as_array('access'));
    
    // d(in_array('profile.*',$access_in_use));
    //     d($access_in_use);

        return $this->render('oc-panel/pages/role/update', array('form' => $form, 
                                                                'controllers' => $controllers,
                                                                'access_in_use'=>$access_in_use));
    }

	
}
