<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Categories widget reader
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Categories extends Widget
{

	public function __construct()
	{	

		$this->title = __('Categories');
		$this->description = __('Display categories');

		$this->fields = array(	
						 		'categories_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Categories title displayed'),
						 		  						'default'   => __('Categories'),
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
        return parent::title($this->categories_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$cat = new Model_Category();

		// loaded category

        if (Model_Category::current()->loaded())
        {
    	    $category = Model_Category::current()->id_category; // id_category
    	    
    	    //list of children of current category
            // if list_cat dosent have siblings take brothers
            $list_cat = $cat->where('id_category_parent','=',$category)->order_by('order','asc')->cached()->find_all();
    	    if(count($list_cat) == 0)
            {
                $list_cat = $cat->where('id_category_parent','=',Model_Category::current()->id_category_parent)->order_by('order','asc')->cached()->find_all();
            }
            //parent of current category
    	   	$cat_parent_deep = $cat->where('id_category','=',Model_Category::current()->id_category_parent)->limit(1)->find();

            // array with name and seoname of a category and his parent. Is to build breadcrumb in widget
    	   	$current_and_parent = array('name'			=> Model_Category::current()->name,
                                        'id'            => Model_Category::current()->id_category,
    	    					        'seoname'		=> Model_Category::current()->seoname,
    	    					        'parent_name'	=> $cat_parent_deep->name,
                                        'id_parent'     => $cat_parent_deep->id_category_parent,
    	    					        'parent_seoname'=> $cat_parent_deep->seoname);
       	}
        else
        {
			$list_cat = $cat->where('id_category_parent','=',1)->order_by('order','asc')->cached()->find_all();
			$current_and_parent = NULL;
        }
        

		$this->cat_items = $list_cat;
		$this->cat_breadcrumb = $current_and_parent;
        

	}


}