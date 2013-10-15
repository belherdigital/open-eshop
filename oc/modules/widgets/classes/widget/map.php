<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Google Maps widget
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Map extends Widget
{

	public function __construct()
	{	

		$this->title = __('Map');
		$this->description = __('Google Maps with ads');

		$this->fields = array(	
								'map_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Map title displayed'),
														'required'	=> FALSE),
                                'map_height'  => array(  'type'      => 'numeric',
                                                        'display'   => 'text',
                                                        'label'     => __('Map height in pixels'),
                                                        'default'   => '200',
                                                        'required'  => FALSE),
                                'map_zoom'  => array(  'type'      => 'numeric',
                                                        'display'   => 'text',
                                                        'label'     => __('Zoom in the map'),
                                                        'default'   => '10',
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
        return parent::title($this->map_title);
    }

}