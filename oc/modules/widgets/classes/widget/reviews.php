<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * reviews widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Reviews extends Widget
{

    public function __construct()
    {   

        $this->title        = __('Product Reviews');
        $this->description  = __('Product Reviews reader');

        $this->fields = array(  'reviews_limit' => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Review Limit, if none display all'),
                                                        'default'   => '5',
                                                        'required'  => FALSE),

                                'review_title'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Review title displayed'),
                                                        'default'   => 'Latest reviews',
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
        return parent::title($this->review_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        $review = new Model_Review();
        $review->where('status', '=', Model_Review::STATUS_ACTIVE)
            ->order_by('created','desc');
        
        if($this->reviews_limit != NULL OR $this->reviews_limit != '')
        $review = $review->limit($this->reviews_limit)->cached()->find_all();
        else
        $review = $review->cached()->find_all();
        //die(print_r($review));
        $this->review = $review;
    }


}