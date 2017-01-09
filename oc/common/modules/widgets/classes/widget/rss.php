<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * RSS widget reader
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_RSS extends Widget
{

	public function __construct()
	{	

		$this->title 		= __('RSS');
		$this->description 	= __('RSS reader with cache');

		$this->fields = array(	'rss_limit' => array( 	'type'		=> 'numeric',
														'display'	=> 'select',
														'label'		=> __('Number of items to display'),
														'options'   => array_combine(range(1,50),range(1,50)), 
														'default'	=> 5,
														'required'	=> TRUE),

								'rss_expire' => array( 	'type'		=> 'numeric',
														'display'	=> 'text',
														'label'		=> __('How often we refresh the RSS, in seconds'),
														'default'	=> 86400,
														'required'	=> TRUE),

						 		'rss_url'  => array(	'type'		=> 'uri',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('RSS url address'),
						 		  						'default'   => 'http://feeds.feedburner.com/yclas',
														'required'	=> TRUE),

						 		'rss_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('RSS title displayed'),
						 		  						'default'   => 'Yclas',
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
        return parent::title($this->rss_title);
    }

	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		//try to get the RSS from the cache
        $rss = Feed::parse($this->rss_url,$this->rss_limit,$this->rss_expire);

		$this->rss_items = $rss;
	}


}