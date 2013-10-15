<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Locations widget reader
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Locations extends Widget
{

	public function __construct()
	{	

		$this->title = __('Locations');
		$this->description = __('Display Locations');

		$this->fields = array(	
						 		'locations_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Locations title displayed'),
						 		  						'default'   => __('Locations'),
														'required'	=> FALSE),
                                'locations' => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Locations'),
                                                        'options'   => array('0'    => __('FALSE'),
                                                                             'popular'   => __('TRUE'),
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
        return parent::title($this->locations_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$loc = new Model_Location();

		// loaded category
		if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
            {
        	    $location = Controller::$location->id_location; // id_location
        	    
        	    //list of children of current location
                // if list_loc dosent have siblings take brothers //
        	    $list_loc = $loc->where('id_location_parent','=',$location)->order_by('order','asc')->cached()->find_all();
        	    if(count($list_loc) == 0)
                {
                    $list_loc = $loc->where('id_location_parent','=',Controller::$location->id_location_parent)->order_by('order','asc')->cached()->find_all();
                }

                //parent of current location
        	   	$loc_parent_deep = $loc->where('id_location','=',Controller::$location->id_location_parent)->limit(1)->find();

                // array with name and seoname of a location and his parent. Is to build breadcrumb in widget
        	   	$current_and_parent = array('name'			=> Controller::$location->name,
        	    					        'id'			=> Controller::$location->id_location,
        	    					        'seoname'		=> Controller::$location->seoname,
        	    					        'parent_name'	=> $loc_parent_deep->name,
        	    					        'id_parent'     => $loc_parent_deep->id_location_parent,
        	    					        'parent_seoname'=> $loc_parent_deep->seoname);
           	}
        }
        else
        {
			$list_loc = $loc->where('id_location_parent','=',1)->order_by('order','asc')->cached()->find_all();
			$current_and_parent = NULL;
        }
        $this->locations = $this->locations;
		$this->loc_items = $list_loc;
		$this->loc_breadcrumb = $current_and_parent;
        $this->cat_seoname = NULL;
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $this->cat_seoname = Controller::$category->seoname;
        }
	}


}