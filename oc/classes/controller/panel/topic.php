<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Topic extends Auth_Crud {

    /**
     * @var $_index_fields ORM fields shown in index
     */
    protected $_index_fields = array('title','created');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'topic';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('update');

    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';
        
        $elements = ORM::Factory($this->_orm_model)->where('id_forum','IS NOT',NULL);//->find_all();

        $pagination = Pagination::factory(array(
                    'view'           => 'pagination',
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
            $view = 'oc-panel/crud/index';
        
        $this->render($view, array('elements' => $elements,'pagination'=>$pagination));
    }

    /**
     * Update new forum
     */
    public function action_update()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Topic')));

        $topic = new Model_Topic($this->request->param('id'));

        $get_all = Model_Forum::get_all();

        //get all forums to build forum parents in select
        $forum_parents = array();
        foreach ($get_all[0] as $parent )
            $forum_parents[$parent['id']] = $parent['name'];

        $this->template->content = View::factory('oc-panel/pages/topic/update', array('topic'=>$topic, 'forum_parents'=>$forum_parents));
        
        if ($_POST)
        {

            $topic->title = core::post('title');
            $topic->id_forum = core::post('id_forum');
            $topic->description = core::post('description');
            if(core::post('seotitle') != $topic->seotitle)
                $topic->seotitle = $topic->gen_seotitle(core::post('seotitle'));

            if(core::post('status') == 'on')
                $topic->status = 1;
            else
                $topic->status = 0;


            try {
                $topic->save();
                Alert::set(Alert::SUCCESS, __('Topic is updated.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'topic','action'=>'index')));  
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, $e->getMessage());
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'topic','action'=>'index'))); 
            }
        }
    }

    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;

        $topic = new Model_Forum($this->request->param('id'));

        //update the elements related to that ad
        if ($topic->loaded())
        {
            try
            {
                $topic->delete();
                $this->template->content = 'OK';
                Alert::set(Alert::SUCCESS, __('Topic deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::SUCCESS, __('Topic not deleted'));

        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'topic','action'=>'index')));  

    }
}
