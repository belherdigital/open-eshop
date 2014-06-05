<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * products widget reader
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Products extends Widget
{

    public function __construct()
    {   

        $this->title        = __('Products');
        $this->description  = __('Products reader');

        $this->fields = array(  'products_type' => array('type'     => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Type products to display'),
                                                        'options'   => array('latest'    => __('Latest products'),
                                                                             'popular'   => __('Popular last month'),
                                                                             'featured'  => __('Featured products'),
                                                                            ), 
                                                        'default'   => 5,
                                                        'required'  => TRUE),

                                'products_limit' => array(   'type'      => 'numeric',
                                                        'display'   => 'select',
                                                        'label'     => __('Number of products to display'),
                                                        'options'   => array_combine(range(1,50),range(1,50)), 
                                                        'default'   => 5,
                                                        'required'  => TRUE),

                                'products_title'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('products title displayed'),
                                                        'default'   => 'Latest products',
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

        switch ($this->products_type) 
        {
            case 'popular':
                $id_products = array_keys(Model_Visit::popular_products());
                if (count($id_products)>0)
                    $products->where('id_product','IN', $id_products);
         
                break;
            case 'featured':
                $products->where('featured','IS NOT', NULL)
                ->where('featured','>', Date::unix2mysql())
                ->order_by('featured','desc');
                break;
            case 'latest':
            default:
                $products->order_by('created','desc');
                break;
        }

        $products = $products->limit($this->products_limit)->cached()->find_all();
        //die(print_r($products));
        $this->products = $products;
    }


}