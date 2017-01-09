<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Social extends Auth_Controller {

    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Social Auth'))->set_url(Route::url('oc-panel',array('controller'  => 'social'))));

    }

	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Social Authentication for login')));
		$this->template->title = __('Social Auth');

        $this->template->styles              = array(
        	'css/sortable.css' => 'screen',
        	'css/pnotify.custom.min.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/pnotify.custom.min.js';
        $this->template->scripts['footer'][] = 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/settings.js';

        //retrieve social_auth values
        $config = Social::get();
        
		if($p = $this->request->post())
		{
			$confit_old = $config;
			$config_new = array();
			foreach ($p as $key => $value) 
			{
				if($key != 'submit')
				{	
					// check if its id, secret .. and build multy d. array, same as they have
					if(strpos($key,'_id'))
						$config_new['providers'][str_replace('_id', '', $key)]['keys']['id'] = $value;
					elseif(strpos($key,'_secret'))
						$config_new['providers'][str_replace('_secret', '', $key)]['keys']['secret'] = $value;
					elseif(strpos($key,'_key'))
						$config_new['providers'][str_replace('_key', '', $key)]['keys']['key'] = $value;
					elseif($key == 'debug_mode')
						$config_new[$key] = $value;
					else
						$config_new['providers'][$key]['enabled'] = $value;
				}
			}
			// two fields not included
			$config_new['base_url']      = Route::url('default',array('controller'=>'social','action'=>'login','id'=>1));
			$config_new['debug_file']    = DOCROOT.'oc/vendor/hybridauth/logs.txt';
			
			$obj_social_config = new Model_Config();
			$conf = $obj_social_config->where('group_name', '=', 'social')
									  ->where('config_key', '=', 'config')
									  ->limit(1)->find();
			if($conf->loaded())
			{	
				$conf->config_value = json_encode($config_new);
				try 
				{
					$conf->save();
                    $config = $config_new;//we update the form values if we changed them
                    Alert::set(Alert::SUCCESS, __('Social Auth updated'));
				} 
				catch (Exception $e) 
				{
					throw HTTP_Exception::factory(500,$e->getMessage());
				}
			
			}
		}

        $this->template->content = View::factory('oc-panel/pages/social_auth/index',array('config'=>$config));

        
	}
}