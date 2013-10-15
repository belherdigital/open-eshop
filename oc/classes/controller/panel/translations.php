<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Translations extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Translations'))->set_url(Route::url('oc-panel',array('controller'  => 'translations'))));
        
    }

    public function action_index()
    {

        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('List')));  
        $this->template->title = __('Translations');

        //scan project files and generate .po
        $parse = $this->request->query('parse');
        if($parse)
        {
            //scan script
            require_once Kohana::find_file('vendor', 'POTCreator/POTCreator','php');

            $obj = new POTCreator;
            $obj->set_root(DOCROOT);
            $obj->set_exts('php');
            $obj->set_regular('/_[_|e]\([\"|\']([^\"|\']+)[\"|\']\)/i');
            $obj->set_base_path('..');
            $obj->set_read_subdir(true);
            
            $obj->write_pot(i18n::get_language_path());
            Alert::set(Alert::SUCCESS, 'File regenerated');
        }

        //change default site language
        if($this->request->param('id'))
        {
         //save language
            $locale = new Model_Config();

            $locale->where('group_name','=','i18n')
                    ->where('config_key','=','locale')
                    ->limit(1)->find();

            if (!$locale->loaded())
            {
                $locale->group_name = 'i18n';
                $locale->config_key = 'locale';
            }

            $locale->config_value = $this->request->param('id');
            try {
                $locale->save();
                Alert::set(Alert::SUCCESS,'');
                Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'translations')));  


            } catch (Exception $e) {
                echo $e;
            }
        }

        $this->template->content = View::factory('oc-panel/pages/translations/index',array('languages' => i18n::get_languages(),
                                                                                            'current_language' => core::config('i18n.locale')
                                                                                            ));

    }

    public function action_edit()
    {
        if($this->request->param('id'))
        {   
            $language   = $this->request->param('id');
            $default    = DOCROOT.'languages/'.$language.'/LC_MESSAGES/messages.po';
            $default_mo = DOCROOT.'languages/'.$language.'/LC_MESSAGES/messages.mo';
        }
        else
             Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'translations')));  


        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Translation')));  
        $this->template->title = __('Edit Translation');     
        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/translations/edit');

        $this->template->scripts['footer'][] = 'js/oc-panel/translations.js';

        $base_translation = i18n::get_language_path();

        //pear gettext scripts
        require_once Kohana::find_file('vendor', 'GT/Gettext','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/PO','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/MO','php');
        //.po to .mo script
        require_once Kohana::find_file('vendor', 'php-mo/php-mo','php');

        //load the .po files
        $pocreator_en = new File_Gettext_PO();
        $pocreator_en->load($base_translation);
        $pocreator_default = new File_Gettext_PO();
        $pocreator_default->load($default);

        //watch out there's a limit of 1000 posts....we have 540...
        if($this->request->post())
        {
            foreach ($pocreator_en->strings as $key=>$string) 
                $keys[] = $key;

            $translations = $this->request->post('translations');

            $strings = array();
            $out = '';
            foreach($translations as $key => $value)
            {
                if($value != "")
                {
                    $strings[$keys[$key]] = $value;
                    $out .= '#: String '.$key.PHP_EOL;
                    $out .= 'msgid "'.$keys[$key].'"'.PHP_EOL;
                    $out .= 'msgstr "'.$value.'"'.PHP_EOL;
                    $out .= PHP_EOL;
                }
            }
            //write the generated .po to file
            $fp = fopen($default, 'w+');
            $read = fwrite($fp, $out);
            fclose($fp);

            $pocreator_default->strings = $strings;

            //generate the .mo from the .po file
            phpmo_convert($default);

             Alert::set(Alert::SUCCESS, $this->request->param('id').' '.__('Language saved'));
            Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'translations','action'=>'edit','id'=>$this->request->param('id'))));  
        }

        $content->edit_language     = $this->request->param('id');
        $content->strings_en        = $pocreator_en->strings;
        $content->strings_default   = $pocreator_default->strings;

    }



}//end of controller