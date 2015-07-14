<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Coupon
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Coupon extends Model_OC_Coupon {


    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'product' => array(
                'model'       => 'product',
                'foreign_key' => 'id_product',
            ),
    );


    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   

        $form->fields['id_product']['display_as']   = 'select';
        $form->fields['id_product']['caption']      = 'title';  

        $form->fields['valid_date']['attributes']['placeholder']        = 'yyyy-mm-dd';
        $form->fields['valid_date']['attributes']['data-toggle']        = 'datepicker';
        $form->fields['valid_date']['attributes']['data-date']          = '';
        $form->fields['valid_date']['attributes']['data-date-format']   = 'yyyy-mm-dd';
    }


}