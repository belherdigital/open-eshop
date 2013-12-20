<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Market seettings
 */


class Controller_Panel_Theme extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Themes'))->set_url(Route::url('oc-panel',array('controller'  => 'theme'))));

    }

    /**
     * theme options/settings
     * @return [view] Renders view with form inputs
     */
    public function action_options()
    {
        $options = NULL;
        $data    = NULL;

        //this is how we manage the mobile options, or if we want to set other theme options without enableing it. ;)
        if($this->request->param('id'))
        {
           $options = Theme::get_options($this->request->param('id'));
           $data    = Theme::load($this->request->param('id'));
        }

        if ($options === NULL)
            $options = Theme::$options;

        if ($data === NULL)
            $data = Theme::$data;


        // validation active 
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Theme Options')));     
        $this->template->title = __('Theme Options');  

        // save only changed values
        if($this->request->post())
        {

            //for each option read the post and store it
            foreach ($_POST as $key => $value) 
            {
                if (isset($options[$key]))
                {
                    //if textarea allow HTML
                    if ($options[$key]['display'] == 'textarea')
                        $data[$key] = Kohana::$_POST_ORIG[$key];
                    else
                        $data[$key] = core::post($key);
                }
            }
            
            Theme::save($this->request->param('id'),$data);
            
            Alert::set(Alert::SUCCESS, __('Theme configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>$this->request->param('id'))));
        }

        $this->template->content = View::factory('oc-panel/pages/themes/options', array('options' => $options, 'data'=>$data));
    }

    /**
     * theme selector
     * @return [view] 
     */
    public function action_index()
    {
        // validation active 
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Themes')));  
        $this->template->title = __('Themes');     
        
        $market_url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.open-eshop.com':'eshop.lo';
        $this->template->scripts['footer'][] = 'http://'.$market_url.'/embed.js';


        //getting the themes
        $themes = Theme::get_installed_themes();

        $mobile_themes = Theme::get_installed_themes(TRUE);
  
        //getting themes from market
        $market = array();
        $json = Core::get_market();
        if(is_array($json))
        {    
            foreach ($json as $theme) 
            {
                //we add only those the user doesn't have installed
                if (strtolower($theme['type']) == 'themes'  
                    AND !in_array(strtolower($theme['seoname']), array_keys($themes))
                    AND !in_array(strtolower($theme['seoname']), array_keys($mobile_themes)) )
                    $market[] = $theme;
            }
        }    

         // save only changed values
        if($this->request->param('id'))
        {
            $theme = $this->request->param('id');

            $opt = Theme::get_options($theme);
            Theme::load($theme,FALSE);

            if (isset($opt['premium']) AND Theme::get('license')==NULL)
            {
                 $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=> 'license','id'=>$theme) ));
            }

            Theme::set_theme($theme);
            
            Alert::set(Alert::SUCCESS, __('Appearance configuration updated'));
            
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=> (!isset($opt['premium']))?'index':'options')));
        }

        $this->template->content = View::factory('oc-panel/pages/themes/theme', array('market' => $market,
                                                                                    'themes' => $themes, 
                                                                                    'mobile_themes' => $mobile_themes,
                                                                                    'selected'=>Theme::get_theme_info(Theme::$theme)));
    }


   /**
     * theme selector
     * @return [view] 
     */
    public function action_license()
    {
        $theme = $this->request->param('id');

        // save only changed values
        if($this->request->post('license'))
        {
            if (Theme::license($this->request->post('license'))==TRUE)
            {
                Theme::set_theme($theme);     
                Theme::$options = Theme::get_options($theme);       
                Theme::load($theme);

                Theme::$data['license']      = $this->request->post('license');
                Theme::$data['license_date'] = time()+7*24*60*60;
                Theme::save($theme);

                Alert::set(Alert::SUCCESS, __('Theme activated, thanks.'));
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=> 'options')));
            }
            else
            {
                Alert::set(Alert::ERROR, __('There was an error activating your license.'));
            }            
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Theme License')));  
        $this->template->title = __('Theme License');     

        $this->template->content = View::factory('oc-panel/pages/themes/license', array('theme' => Theme::$theme));
    }
    

    /**
     * mobile theme selector
     * @return [view] 
     */
    public function action_mobile()
    {

        // save only changed values
        if($this->request->param('id'))
        {
            Theme::set_mobile_theme($this->request->param('id'));
            
            Alert::set(Alert::SUCCESS, __('Mobile Theme updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=>'index')));
        }

       
    }

    /**
    * install theme from selected zip file
    * @return redirect 
    */
    public function action_install_theme()
    {
        $zip_theme = $_FILES['theme_file']; //file post
        
        if (!Upload::type($zip_theme, array('zip'))) //check if it si of a right type
        {
            Alert::set(Alert::ALERT, $zip_theme['name'].' '.__('Is not valid format, please use ZIP format'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme', 'action'=>'index')));
        }
        else
        {
            if($zip_theme != NULL) // sanity check 
            {   
                // saving/uploadng zip file to dir.
                $root = DOCROOT.'themes/'; //root folder
            
                 // save file to root folder, file, name, dir
                $file = Upload::save($zip_theme, $zip_theme['name'], $root);

                $zip = new ZipArchive;

                // open zip file, and extract to root dir
                if ($zip_open = $zip->open($root.$zip_theme['name'])) 
                {
                    $zip->extractTo($root);
                    $zip->close();
                    unlink($root.$zip_theme['name']);
                } 
                else 
                {
                    Alert::set(Alert::ALERT, $zip_theme['name'].' '.__('Zip file faild to extract, please try again.'));
                    $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme', 'action'=>'index')));
                }

                Alert::set(Alert::SUCCESS, $zip_theme['name'].' '.__('You have succesfully installed the theme!'));
                $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme', 'action'=>'index')));
            }
            
        }
    }

}//end of controller