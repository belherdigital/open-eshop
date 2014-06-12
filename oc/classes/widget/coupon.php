<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Coupon widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Coupon extends Widget
{

	public function __construct()
	{	

		$this->title = __('Coupon');
		$this->description = __('Apply discounts.');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
                                                        'default'   => __('Coupon'),
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