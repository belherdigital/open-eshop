<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Languages widget selector
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Languages extends Widget
{

	public function __construct()
	{	

		$this->title = __('Languages');
		$this->description = __('Choose language');

		$this->fields = array(	
						 		'languages_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Languages title displayed'),
						 		  						'default'   => __('Languages'),
														'required'	=> FALSE),
                                'languages' => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Languages to display coma separated'),
                                                        'required'  => FALSE),

						 		);
	}


    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->languages_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{  
        //we need this option enabled for this plugin to work
        if (Core::config('i18n.allow_query_language')==0)
            Model_Config::set_value('i18n','allow_query_language',1);

		if ($this->languages == '')
            $this->languages = i18n::get_languages();
        else
            $this->languages = array_map('trim',explode(',',$this->languages));
        
	}


}