<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Forum extends Auth_Crud {
	
	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('name','order','price', 'id_forum', 'id_forum_parent');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'forum';	


    /**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_index($view = NULL)
    {
        //template header
        $this->template->title  = __('Forums');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Forums')));
        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/forums.js';

        list($forums,$order)  = Model_Forum::get_all();

        $this->template->content = View::factory('oc-panel/pages/forums',array('forums' => $forums,'order'=>$order));
    }


    /**
     * saves the forum in a specific order and change the parent
     * @return void 
     */
    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $forum = new Model_Forum(core::get('id_forum'));

        if ($forum->loaded())
        {
            //saves the current forum
            $forum->id_forum_parent = core::get('id_forum_parent');
            $forum->parent_deep     = core::get('deep');
            

            //saves the forums in the same parent the new orders
            $order = 0;
            foreach (core::get('brothers') as $id_forum) 
            {
                $id_forum = substr($id_forum,3);//removing the li_ to get the integer

                //not the main forum so loading and saving
                if ($id_forum!=core::get('id_forum'))
                {
                    $c = new Model_Forum($id_forum);
                    $c->order = $order;
                    $c->save();
                }
                else
                {
                    //saves the main forum
                    $forum->order  = $order;
                    $forum->save();
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

        $forum = new Model_Forum($this->request->param('id'));

        //update the elements related to that ad
        if ($forum->loaded())
        {
            //update all the siblings this forum has and set the forum parent
            $query = DB::update('forums')
                        ->set(array('id_forum_parent' => $forum->id_forum_parent))
                        ->where('id_forum_parent','=',$forum->id_forum)
                        ->execute();

            //update all the posts this forum has and set the forum parent
            $query = DB::update('posts')
                        ->set(array('id_forum' => $forum->id_forum_parent))
                        ->where('id_forum','=',$forum->id_forum)
                        ->execute();

            try
            {
                $forum->delete();
                $this->template->content = 'OK';
                Alert::set(Alert::SUCCESS, __('Forum deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::SUCCESS, __('Forum not deleted'));

        
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'forum','action'=>'index')));  

    }

}
