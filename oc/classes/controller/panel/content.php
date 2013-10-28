<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Content extends Auth_Controller {


    public function action_content()
    {
    	// validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('content')));  
        $this->template->title = __('contents');
        
        $type = $this->request->query('type');
        $locale = ($this->request->query('locale_select')) ? $this->request->query('locale_select') : NULL ;

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
        			->order_by('order','desc')
                	->as_object()
                	->cached()
                	->execute();
        $l_locale = array();
		foreach ($ll as $key => $l) 
		{
			$l_locale[$l->locale] = $l->locale;
		}
		
        $this->template->content = View::factory('oc-panel/pages/content/content',array('contents'=>$contents, 
        																				'type'=>$type, 
        																				'locale_list'=>$l_locale));
    }

}
