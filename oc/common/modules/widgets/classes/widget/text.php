<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Text widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Text extends Widget
{

	public function __construct()
	{	

		$this->title = __('Text');
		$this->description = __('HTML text area');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Text title displayed'),
														'required'	=> FALSE),
								//@todo allow HTML in the body
						 		'text_body'  => array(	'type'		=> 'textarea',
						 		  						'display'	=> 'textarea',
						 		  						'label'		=> __('HTML/text content here'),
														'required'	=> TRUE),

						 		
						 		);
	}


    /**
     * saves current widget data into the DB config
     * @param string $old_placeholder
     * @return boolean 
     */
    public function save($old_placeholder = NULL)
    {
        if (isset(Kohana::$_POST_ORIG['text_body']))
            $this->text_body = Kohana::$_POST_ORIG['text_body'];

        return parent::save($old_placeholder);
    }


    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->text_title);
    }

}