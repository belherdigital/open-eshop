<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper class to display the widgets
 *
 * @package    OC
 * @category   Widget
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Widgets {
	

	/**  
	 * @var array of widget placeholders in theme, @ /themes/THEMENAME/init.php
	 */
	public static $theme_placeholders = array();

	/**
	 * @var array of default placeholders, @ /modules/widgets/init.php
	 */
	public static $default_placeholders = array();
	
	/**widgets	 * @var array of widget specific to theme, @ /themes/THEMENAME/init.php
	 */
	public static $theme_widgets = array();

	/**
	 * @var array of default widgets, @ /modules/widgets/init.php
	 */
	public static $default_widgets = array();
	
	/**
	 * Gets from conf DB json object of active widgets
	 * @param  string $name_placeholder name of placeholder
	 * @param bool $only_names, returns only an array with the widgets names
	 * @return array widgets
	 */
	public static function get($name_placeholder, $only_names = FALSE)
	{
		$widgets = array();

		$active_widgets = core::config('placeholder.'.$name_placeholder);

		if($active_widgets!==NULL AND !empty($active_widgets) AND $active_widgets !== '[]' AND $active_widgets !== '[""]' AND $active_widgets !== '""')
		{ 
			
			$active_widgets = json_decode($active_widgets, TRUE);
			
			// array of widget path, to include to view
			foreach ($active_widgets as $widget_name) 
			{	
				if ($only_names)
				{
					$widgets[] = $widget_name;
				}
				else
				{
                    if (($w = Widget::factory($widget_name))!==NULL)
                        $widgets[] = $w;
				}
				
				
			}//end for

		} //end if widgets
		
		
		return $widgets;
	}

    /**
     * shortcut that returns already the widgets rendered, but only those that can be rendered!!
     * @param  string $name_placeholder 
     * @return array                   
     */
    public static function render($name_placeholder)
    {
        $renders = array();

        $widgets = self::get($name_placeholder);

        foreach ($widgets as $widget) 
        {
            //only if renders returns something
            if ( ($out = $widget->render())!==FALSE )
                $renders[] = $out;
        }

        return $renders;
    }

	/**
	 * returns all the widgets 
	 * @param bool $only_names, returns only an array with the widgets names, if not array with widgets instances
	 * @return array 
	 */
	public static function get_widgets($only_names = FALSE)
	{
		$widgets = array();
        
        Widgets::$default_widgets = self::get_installed_widgets();

        //merge the possible widgets of the theme
		$list = array_unique(array_merge(widgets::$default_widgets, widgets::$theme_widgets));

		if ($only_names)
			return $list;

		 //creating an instance of each widget
        foreach ($list as $widget_name) 
        {
            if (class_exists($widget_name))
                $widgets[] = new $widget_name; 
        }

        return $widgets;
	}

    /**
     * get the widgets that he finds in the folder
     * @return array 
     */
    public static function get_installed_widgets()
    {
        $widgets = array();


        //check directory for widgets for this project
        foreach (new DirectoryIterator(APPPATH.'classes/widget') as $file) 
        {
            if($file->isFile())
                $widgets[] = 'widget_'.$file->getBasename('.php');            
        }

        //check directory for common widgets
        foreach (new DirectoryIterator(COMMONPATH.'modules/widgets/classes/widget') as $file) 
        {
            if($file->isFile())
                $widgets[] = 'widget_'.$file->getBasename('.php');            
        }

        return $widgets;
    }

	/**
	 * returns placeholders names + widgets
	 * @param bool $only_names, returns only an array with the placeholders names, if not array with widgets instances
	 * @return array 
	 */
	public static function get_placeholders($only_names = FALSE)
	{
		$placeholders = array();

		$list = array_unique(array_merge(widgets::$default_placeholders, widgets::$theme_placeholders));

		//This is a forced placeholders for those widgets that we don't want to lose.
		$list[] = 'inactive';

		if ($only_names)
			return $list;

		//get the widgets for the placeolders
        foreach ($list as $placeholder) 
        	$placeholders[$placeholder] = widgets::get($placeholder);

        return $placeholders;
        
	}

}//end class Widget