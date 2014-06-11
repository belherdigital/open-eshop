<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Extended functionality for Kohana View
 *
 * @package    OC
 * @category   View
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class View extends OC_View{
    
    /**
     * gets the fragment name, unique using i18n theme and skin and cat and loc
     * @param  string $name 
     * @return string       
     */
    public static function fragment_name($name)
    {
        $cat_seoname = '';

        if (Model_Category::current()->loaded())
            $cat_seoname = '_category_'.Model_Category::current()->seoname;
        
        return 'fragment_'.$name.'_'.i18n::lang().'_'.Theme::$theme.$cat_seoname; //.Theme::$skin
    }
    
}