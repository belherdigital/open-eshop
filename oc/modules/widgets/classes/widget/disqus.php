<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Disqus widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Disqus extends Widget
{

	public function __construct()
	{	

		$this->title = __('Disqus');
		$this->description = __('Add latest comments');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
                                                        'default'   => __('Comments'),
						 		  						'label'		=> __('Title displayed'),
														'required'	=> FALSE),
						 		'comments_limit'=>array('type'      => 'numeric',
                                                        'display'   => 'select',
                                                        'label'     => __('Number of comments to display'),
                                                        'options'   => array_combine(range(1,50),range(1,50)), 
                                                        'default'   => 3,
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