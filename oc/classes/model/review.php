<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product reviews
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Review extends ORM {

    /**
     * status constants
     */
    const STATUS_NOACTIVE = 0; 
    const STATUS_ACTIVE   = 1; 

    const RATE_MAX   = 5; 
    
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'reviews';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_review';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'product' => array(
                'model'       => 'product',
                'foreign_key' => 'id_product',
            ),
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'order' => array(
                'model'       => 'order',
                'foreign_key' => 'id_order',
            ),
    );



    public function exclude_fields()
    {
        return array('created');
    }

    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   

        $form->fields['id_product']['display_as']   = 'select';
        $form->fields['id_product']['caption']      = 'title';  
        $form->fields['id_user']['display_as']      = 'text';
        $form->fields['id_order']['display_as']     = 'text';
        $form->fields['description']['display_as']   = 'textarea';
    }

    /**
     * returns the product rate from all the reviews
     * @param  Model_Product $product [description]
     * @return [type]                 [description]
     */
    public static function get_product_rate(Model_Product $product)
    {
        //visits created last XX days
        $query = DB::select(DB::expr('SUM(rate) rate'))
                        ->select(DB::expr('COUNT(id_product) total'))
                        ->from('reviews')
                        ->where('id_product','=',$product->id_product)
                        ->where('status','=',Model_Review::STATUS_ACTIVE)
                        ->group_by('id_product')
                        ->execute();

        $rates = $query->as_array();

        return (isset($rates[0]))?round($rates[0]['rate']/$rates[0]['total'],2):FALSE;

    }

    /**
     * returns best rated products
     * @param  Model_Product $product [description]
     * @return [type]                 [id]
     */
    public static function best_rated()
    {
        $query = DB::select('id_product',DB::expr('ROUND(SUM(rate)/COUNT(id_product)) rate'))
                        // ->select(DB::expr('COUNT(id_product) total'))
                        ->from('reviews')
                        ->where('status','=',Model_Review::STATUS_ACTIVE)
                        ->group_by(DB::expr('id_product'))
                        ->order_by('rate','desc')
                        ->execute();

        return $rates = $query->as_array();
    }




}