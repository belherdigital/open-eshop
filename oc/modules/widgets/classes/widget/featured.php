<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Categories widget reader
 *
 * @author      Slobodan <slobodan.josifovic@gmail.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Featured extends Widget
{

	public function __construct()
	{	

		$this->title = __('Featured Ads');
		$this->description = __('Display Featured Ads');

		$this->fields = array(	
						 		'featured_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Featured title displayed'),
						 		  						'default'   => __('Featured'),
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
        return parent::title($this->featured_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$ads = new Model_Ad();
        $ads->where('status','=', Model_Ad::STATUS_PUBLISHED);

        $ads->where('featured','IS NOT', NULL)
        ->where('featured','>', DB::expr('NOW()'))
        ->order_by('featured','desc');
         

        $ads = $ads->limit($this->ads_limit)->cached()->find_all();
         
        $this->ads = $ads;

	}


}