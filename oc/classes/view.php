<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Extended functionality for Kohana View
 *
 * @package    OC
 * @category   View
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class View extends Kohana_View{
    
    /**
     * gets a cached fragment view
     * @param  string $name name to use in the cache should be unique
     * @param  string $file file view
     * @param  array $data 
     * @return string       
     */
    public static function fragment($name, $file = NULL, array $data = NULL)
    {
        $name = self::fragment_name($name);

        if ( ($fragment = Core::cache($name))===NULL ) 
        {
            //if file is set and we dont have the cache we render.
            if ($file!==NULL)
            {
                $view = View::factory($file,$data);
                $fragment = $view->render();
                Core::cache($name,$fragment);
            }
            
        }   

        return $fragment;
    }

    /**
     * deletes from cache a fragment
     * @param  string $name 
     * @return bool       
     */
    public static function delete_fragment($name)
    {
        return Core::cache(self::fragment_name($name),NULL,0);
    }

    
    /**
     * gets the fragment name, unique using i18n theme and skin and cat and loc
     * @param  string $name 
     * @return string       
     */
    public static function fragment_name($name)
    {
        $cat_seoname = '';
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $cat_seoname = '_category_'.Controller::$category->seoname;
        }

        return 'fragment_'.$name.'_'.i18n::lang().'_'.Theme::$theme.$cat_seoname; //.Theme::$skin
    }


    /**
     * Sets the view filename. Override from origianl to use from theme folder
     *
     *     $view->set_filename($file);
     *
     * @param   string  view filename
     * @return  View
     * @throws  View_Exception
     */
    public function set_filename($file)
    {
        //folder loaded as module in the bootstrap :D
        if (($path = Kohana::find_file(Theme::views_path(), $file)) === FALSE)
        {
            //in case view not found try to read from default theme
            if (($path = Kohana::find_file(Theme::default_views_path(), $file)) === FALSE)
            {
                //still not found :(, try from cascading system
                if (($path = Kohana::find_file('views', $file)) === FALSE)
                {
                    //d($file);
                    throw new View_Exception('The requested view :file could not be found', array(
                                            ':file' => $file,
                    ));
                }
            }
    
        }
    
        // Store the file path locally
        $this->_file = $path;
    
        return $this;
    }

    
}