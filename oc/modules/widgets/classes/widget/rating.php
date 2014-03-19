<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * rating widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Rating extends Widget
{

    public function __construct()
    {   

        $this->title        = __('Best Rated ');
        $this->description  = __('Best rated products');

        $this->fields = array(  'products_limit' => array(   'type'      => 'numeric',
                                                        'display'   => 'select',
                                                        'label'     => __('Number of products to display'),
                                                        'options'   => array_combine(range(1,50),range(1,50)), 
                                                        'default'   => 5,
                                                        'required'  => TRUE),

                                'products_title'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Best rated title displayed'),
                                                        'default'   => 'Best rated products',
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
        return parent::title($this->products_title);
    }

    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        $products = new Model_Product();
        $products->where('status','=', Model_Product::STATUS_ACTIVE);

        $products->where('rate','IS NOT', NULL)
                ->order_by('rate','desc')
                ->order_by('created', 'asc');

        $products = $products->limit($this->products_limit)->cached()->find_all();
        
        $this->products = $products;
    }


}