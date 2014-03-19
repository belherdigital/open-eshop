<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * forums widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Forum extends Widget
{

    public function __construct()
    {   

        $this->title        = __('Forum');
        $this->description  = __('Forum reader');

        $this->fields = array(  'forums_limit' => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Forum Limit, if none display all'),
                                                        'default'   => '',
                                                        'required'  => FALSE),

                                'forum_title'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('forum title displayed'),
                                                        'default'   => 'Latest forum',
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
        return parent::title($this->forum_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        $forum = new Model_Forum();
        $forum->order_by('created','desc');
        
        if($this->forums_limit != NULL OR $this->forums_limit != '')
        $forum = $forum->limit($this->forums_limit)->cached()->find_all();
        else
        $forum = $forum->cached()->find_all();
        //die(print_r($forum));
        $this->forum = $forum;
    }


}