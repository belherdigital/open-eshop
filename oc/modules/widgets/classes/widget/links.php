<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Ads widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Links extends Widget
{

	public function __construct()
	{	

		$this->title 		= __('Links');
		$this->description 	= __('Links');

        $this->fields = array(	'links_title'  => array(   'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Links title displayed'),
                                                        'default'   => __('Links'),
                                                        'required'  => FALSE),
                                'url' => array( 'type'      => 'textarea',
                                                'display'   => 'textarea',
                                                'label'     => __('Add as many URL|NAME(http://open-classifieds.com|OpenC ) here, and separate with new line'), 
                                                'default'   => '',
                                                'required'  => TRUE),
                                'target' => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Target'),
                                                        'options'   => array('_blank'    => __('New tab or window'),
                                                                             '_parent'   => __('Parent frame'),     
                                                                            ), 
                                                        'default'   => 0,
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
        return parent::title($this->links_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        
        $urls = explode("\n", $this->url);
        
        $url_name = array();
        foreach ($urls as $key ) {
            if($key != '')
                $url_name[] = explode('|', $key);
        }
        // d(print_r($url_name));
        $this->url = $url_name;  
        $this->target = $this->target;
    }


}