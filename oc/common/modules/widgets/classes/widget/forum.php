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

        $this->title        = __('Forum Topics');
        $this->description  = __('Forum topic reader');

        $this->fields = array(  'topics_limit' => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Forum Limit, if none display all'),
                                                        'default'   => '5',
                                                        'required'  => FALSE),

                                'topic_title'  => array(  'type'      => 'text',
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
        return parent::title($this->topic_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        $topic = new Model_Post();
        $topic = $topic->where('status','=',Model_Post::STATUS_ACTIVE)
                ->where('id_post_parent','IS',NULL)
                ->where('id_forum','IS NOT', NULL)
                ->order_by('created','desc');
        
        if($this->topics_limit != NULL OR $this->topics_limit != '')
            $topic = $topic->limit($this->topics_limit)->cached()->find_all();
        else
            $topic = $topic->cached()->find_all();
        //die(print_r($topic));
        $this->topic = $topic;
    }


}