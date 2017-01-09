<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * RSS widget reader
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Pages extends Widget
{

	public function __construct()
	{	

		$this->title = __('Pages');
		$this->description = __('Display CMS pages');

		$this->fields = array(	
						 		'page_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Page title displayed'),
						 		  						'default'   => __('Pages'),
														'required'	=> FALSE),
						 		);
	}


    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->page_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$this->page_items = Model_Content::get_pages();
	}


}