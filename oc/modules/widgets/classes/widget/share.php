<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Share widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Share extends Widget
{

	public function __construct()
	{	

		$this->title = __('Share');
		$this->description = __('Share on social networks.');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
                                                        'default'   => __('Share'),
						 		  						'label'		=> __('Title displayed'),
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
        return parent::title($this->text_title);
    }

}