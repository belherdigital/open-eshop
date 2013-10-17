<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User products
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Product extends ORM {

     /**
     * status constants
     */
    const STATUS_NOPUBLISHED = 0; //first status of the item, not published. This status send ad to moderation always. Until it gets his status changed 
    const STATUS_PUBLISHED   = 1; // ad it's available and published
  
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'products';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_product';


        protected $_belongs_to = array(
        'user'       => array('foreign_key' => 'id_user'),
        'category'   => array('foreign_key' => 'id_category'),
    );

    public function form_setup($form)
    {
       
    }

    public function exclude_fields()
    {
    
    }
}