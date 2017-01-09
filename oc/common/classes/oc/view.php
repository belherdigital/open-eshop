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

class OC_View extends Kohana_View{
    
    /**
     * gets a cached fragment view
     * @param  string $name name to use in the cache should be unique
     * @param  string $file file view
     * @param  array $data 
     * @return string       
     */
    public static function fragment($name, $file = NULL, array $data = NULL)
    {
        //loged in users we do not return cached version
        if ( Auth::instance()->logged_in() AND $file!==NULL)
            return View::factory($file,$data)->render();

        //name of fragment
        $name = self::fragment_name($name);

        //if file is set and we dont have the cache we render.
        if ( ($fragment = Core::cache($name))===NULL AND $file!==NULL)
        {
            $fragment = View::factory($file,$data)->render();
            Core::cache($name,$fragment);
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
     * gets the fragment name, unique using i18n theme and URL
     * @param  string $name 
     * @return string       
     */
    public static function fragment_name($name)
    {
        return 'fragment_'.$name.'_'.i18n::lang().'_'.Theme::$theme.'_'.URL::title(URL::current());
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
        //try to load the file from the selected theme
        $path = Kohana::find_file(Theme::views_path(), $file);

        //if file does not exists on this theme and theme has a parent (its a child theme)
        if ($path === FALSE AND isset(Theme::$parent_theme) AND Theme::$parent_theme!==NULL)
            $path = Kohana::find_file(Theme::views_parent_path(), $file);
    
        //in case view not found try to read from default theme
        if ($path === FALSE)
            $path = Kohana::find_file(Theme::default_views_path(), $file);
        
        //still not found :(, try from cascading system
        if ($path === FALSE)
            $path = Kohana::find_file('views', $file);

        //ok not found too bad :'(
        if ($path === FALSE)
            throw new View_Exception('The requested view :file could not be found', array(':file' => $file));
    
        // Store the file path locally
        $this->_file = $path;
    
        return $this;
    }

    
}
