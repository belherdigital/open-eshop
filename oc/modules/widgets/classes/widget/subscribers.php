<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Ads widget reader
 *
 * @author      Slobodan <slobodan.josifovic@gmail.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Subscribers extends Widget
{

	public function __construct()
	{	

		$this->title 		= __('Subscribe');
		$this->description 	= __('Subscribe for categories');

        $this->fields = array(	'categories' => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Categories'),
                                                        'options'   => array('0'    => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ), 
                                                        'default'   => 0,
                                                        'required'  => TRUE),

                                'locations' => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Locations'),
                                                        'options'   => array('0'    => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ), 
                                                        'default'   => 0,
                                                        'required'  => TRUE),

                                'price' => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Price'),
                                                        'options'   => array('0'    => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ), 
                                                        'default'   => 0,
                                                        'required'  => TRUE),

                                'subscribe_title'  => array(   'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Subscribe title displayed'),
                                                        'default'   => __('Subscribe'),
                                                        'required'  => FALSE),

                                'min_price'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Minimum Price'),
                                                        'default'   => __('0'),
                                                        'required'  => TRUE),

                                'max_price'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Maximum Price'),
                                                        'default'   => __('1000'),
                                                        'required'  => TRUE),
                                'step'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Increment Step'),
                                                        'default'   => __('100'),
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
        return parent::title($this->subscribe_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {

       
        // get all categories
        if ($this->categories != FALSE)
        {
            // loaded category
            list($categories,$order_categories)  = Model_Category::get_all();

            $arr_cat = array();
            foreach ($categories as $cat => $value) 
            {
                if($value['id'] != 1)
                    $arr_cat[$value['id']] = $value['name'];
            }
            
            $this->cat_items = $categories;
            $this->cat_order_items = $order_categories;

        }

        // get all locations
        if ($this->locations != FALSE)
        {
            list($locations,$order_locations)  = Model_Location::get_all();

            $this->loc_items = $locations;
            $this->loc_order_items = $order_locations;
        }

        if($this->price != FALSE)
        {
            $this->price = TRUE;
        }

        // user 
        if(Auth::instance()->logged_in())
        {
            //subscriber
            // check if user is already subscribed 
            $user_id = Auth::instance()->get_user()->id_user;
            $obj_subscriber = new Model_Subscribe();
            $subscriber = $obj_subscriber->where('id_user', '=', $user_id)->limit(1)->find();

            if($subscriber->loaded())
                $this->subscriber = TRUE;

            //if user logged in pass email and id
            $this->user_email = Auth::instance()->get_user()->email;
            $this->user_id = $user_id;
        }
        else
        {
            $this->user_id = 0;
        }
        //min - max price selected
        $this->min_price = $this->min_price;
        $this->max_price = $this->max_price;
    }


}