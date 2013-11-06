<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Content extends Auth_Controller {

    /**
     * action: LIST
     */
    public function action_list()
    {
        
        
        $type = $this->request->query('type');
        $site = ($type == 'page')?__('Page'):__('Email');
        $locale = ($this->request->query('locale_select')) ? $this->request->query('locale_select') : NULL ;

        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title($site));  
        $this->template->title = __('contents');

        if(Model_Content::get_contents($type,$locale)->count() != 0)
            $contents = Model_Content::get_contents($type,$locale);
        else
        {
            Alert::set(Alert::INFO, __('You dont have any ').$type.' for '.$locale.'!');
            $contents = Model_Content::get_contents($type,'en_UK');
        }
        
        $ll = DB::select(DB::expr('DISTINCT (locale)'))
                ->from('content')
                ->where('type','=',$type)
                    ->order_by('locale')
                    ->as_object()
                    ->cached()
                    ->execute();

        $l_locale = array(''=>'');
        foreach ($ll as $key => $l) 
            $l_locale[$l->locale] = $l->locale;
        
        $this->template->content = View::factory('oc-panel/pages/content/list',array('contents'=>$contents, 
                                                                                        'type'=>$type, 
                                                                                        'locale_list'=>$l_locale));
    }

    /**
     * action: EDIT
     */
    public function action_create()
    {
        $type = $this->request->query('type');
        $site = ($type == 'page')?__('Page'):__('Email');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Create').' '.$site));
        $content = new Model_Content();

        $languages = i18n::get_languages();

        $this->template->content = View::factory('oc-panel/pages/content/create', array('locale'=>$languages, 
                                                                                        'type'=>$type));

        if($p = $this->request->post())
        {
            foreach ($p as $name => $value) 
            {
                if($name != 'submit')
                {
                    $content->$name = $value;
                }
            }
            // if status is not checked, it is not set as POST response
            $content->status = (isset($p['status']))?1:0;
            $content->seotitle = $content->gen_seotitle($this->request->post('title'));

            try 
            {
                $content->save();
                Alert::set(Alert::SUCCESS, $this->request->post('type').' '.__('is created'));
                Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'content','action'=>'list')).'?type='.$p['type'].'&locale_select='.$p['locale']);
            } 
            catch (Exception $e) 
            {
                Alert::set(Alert::ERROR, $e->getMessage());
                Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'content','action'=>'list')).'?type='.$p['type'].'&locale_select='.$p['locale']);
            }
        }

    }

    /**
     * action: EDIT
     */
    public function action_edit()
    {

        $id = $this->request->param('id');
        $content = new Model_Content($id);

        $type = $content->type;
        $site = ($type == 'page')?__('Page'):__('Email');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit').' '.$site));
        
        $locale = $content->locale;
        if ($content->loaded())
        {
            $languages = i18n::get_languages();

            $this->template->content = View::factory('oc-panel/pages/content/edit',array('cont'=>$content,'locale'=>$languages));

            if($p = $this->request->post())
            {
                foreach ($p as $name => $value) 
                {
                    if($name != 'submit')
                    {
                        $content->$name = $value;
                    }
                }
                // if status is not checked, it is not set as POST response
                $content->status = (isset($p['status']))?1:0;
                $content->seotitle = $content->gen_seotitle($this->request->post('title'));

                try 
                {
                    $content->save();
                    Alert::set(Alert::SUCCESS, $content->type.' '.__('is edited'));
                    Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'content','action'=>'edit', 'id'=>$content->id_content)));
                } 
                catch (Exception $e) 
                {
                    Alert::set(Alert::ERROR, $e->getMessage());
                }
            }
        }
        else
        {
            Alert::set(Alert::INFO, __('Faild to load content'));
            Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'content','action'=>'edit')).'?type='.$type.'&locale_select='.$locale); 
        }
    }

    /**
     * action: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;
        
        $id = $this->request->param('id');
        $content = new Model_Content($id);

        $type = $content->type;
        $locale = $content->locale;
        
        if ($content->loaded())
        {
            try
            {
                $content->delete();
                $this->template->content = 'OK';
                Alert::set(Alert::SUCCESS, __('Content is deleted'));
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::INFO, __('Content is not deleted'));

        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'content','action'=>'list')).'?type='.$type.'&locale_select='.$locale);  
    }

}
