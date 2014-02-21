<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Chat widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Chat extends Widget
{

	public function __construct()
	{	

		$this->title = __('Chat');
		$this->description = __('Add a chat room');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
                                                        'default'   => __('Chat room'),
						 		  						'label'		=> __('Title displayed'),
														'required'	=> FALSE),
                                'channel'  => array( 'type'      => 'text',
                                                        'display'   => 'text',
                                                        'default'   => URL::title(Core::config('general.base_url')),
                                                        'label'     => __('Name of your chat room/channel'),
                                                        'required'  => FALSE),
						 		'height'=>array('type'      => 'numeric',
                                                        'display'   => 'text',
                                                        'label'     => __('Chat height in PX'),
                                                        'default'   => 400,
                                                        'required'  => TRUE),
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