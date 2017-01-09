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

        $this->template->content = View::factory('oc-panel/pages/forum/forums',array('forums' => $forums,'order'=>$order));
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
            Core::delete_cache();

            $this->template->content = __('Saved');
        }
        else
            $this->template->content = __('Error');


    }

    /**
     * Create new forum
     */
    public function action_create()
    {

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Create new forum')));
        
        $forum = new Model_Forum();

        $get_all = Model_Forum::get_all();

        //get all forums to build forum parents in select
        $forum_parents = array();
        foreach ($get_all[0] as $parent )
            $forum_parents[$parent['id']] = $parent['name'];
        
        $this->template->content = View::factory('oc-panel/pages/forum/create', array('forum_parents'=>$forum_parents));
        
        if ($_POST)
        {
            
            $forum->name = core::post('name');
            $forum->id_forum_parent = core::post('id_forum_parent');
            $forum->description = core::post('description');
            if(core::post('seoname') != "")
                $forum->seoname = $forum->gen_seoname(core::post('name'));
            else
                $forum->seoname = $forum->gen_seoname(core::post('seoname'));
            
            try {
                $forum->save();
                Core::delete_cache();
                Alert::set(Alert::SUCCESS, __('Forum is created.'));
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'forum','action'=>'index')));  
        }
    }

    /**
     * Create new forum
     */
    public function action_update()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit forum')));

        $forum = new Model_Forum($this->request->param('id'));

        $get_all = Model_Forum::get_all();

        //get all forums to build forum parents in select
        $forum_parents = array();
        foreach ($get_all[0] as $parent )
        {
            if ($parent['id']!=$forum->id_forum)
                $forum_parents[$parent['id']] = $parent['name'];
        }
        
        if ($_POST AND $forum->loaded())
        {
            
            $forum->name = core::post('name');
            $forum->id_forum_parent = core::post('id_forum_parent');
            $forum->description = core::post('description');
            if(core::post('seoname') != $forum->seoname)
                $forum->seoname = $forum->gen_seoname(core::post('seoname'));
            
            try {
                $forum->update();
                Core::delete_cache();
                Alert::set(Alert::SUCCESS, __('Forum is updated.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'forum','action'=>'index')));  
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, $e->getMessage());
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'forum','action'=>'index'))); 
            }
        }
        else
            $this->template->content = View::factory('oc-panel/pages/forum/update', array('forum_parents'=>$forum_parents,
                                                                                      'forum'=>$forum));
        
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
            try
            {
                $forum->delete();
                $this->template->content = 'OK';
                Core::delete_cache();
                Alert::set(Alert::SUCCESS, __('Forum deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::ERROR, __('Forum not deleted'));

        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'forum','action'=>'index')));  

    }

}
