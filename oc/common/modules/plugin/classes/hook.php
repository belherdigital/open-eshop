<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Action Hooks 
 *  
 * Hooks are global to the application 
 *  
 * Adding action to a hoook: 
 * Hook::add_action('unique_name_hook','some_class::hook_test'); 
 * OR shortcut: 
 * add_action('unique_name_hook','other_class::hello'); 
 * add_action('unique_name_hook','some_public_function'); 
 *   
 * Performing all the actions for the hook 
 * do_action('unique_name_hook');//you can use too Hook::do_action(); 
 * @package    OC/Plugin
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Hook 
{ 
    //action hooks array    
    public static $actions = array(); 
  
    /** 
     * ads a function to an action hook 
     * @param $hook 
     * @param $function 
     */ 
    public static function add_action($hook,$function) 
    {     
        $hook=mb_strtolower($hook); 
        // create an array of function handlers if it doesn't already exist 
        if(!self::exists_action($hook)) 
        { 
            self::$actions[$hook] = array();  
        } 
  
        // append the current function to the list of function handlers 
        if (is_callable($function)) 
        { 
            self::$actions[$hook][] = $function; 
            return TRUE; 
        }  
  
        return FALSE ; 
    } 
  
    /** 
     * executes the functions for the given hook 
     * @param string $hook 
     * @param array $params 
     * @return boolean true if a hook was setted 
     */ 
    public static function do_action($hook,$params=NULL) 
    { 
        $hook=mb_strtolower($hook); 
        if(isset(self::$actions[$hook])) 
        { 
            // call each function handler associated with this hook 
            foreach(self::$actions[$hook] as $function) 
            { 
                if (is_array($params)) 
                { 
                    call_user_func_array($function,$params); 
                } 
                else  
                { 
                    call_user_func($function); 
                } 
                //cant return anything since we are in a loop! dude! 
            } 
            return TRUE; 
        } 
        return FALSE; 
    } 
  
    /** 
     * gets the functions for the given hook 
     * @param string $hook 
     * @return mixed  
     */ 
    public static function get_action($hook) 
    { 
        $hook=mb_strtolower($hook); 
        return (isset(self::$actions[$hook]))? self::$actions[$hook]:FALSE; 
    } 
  
    /** 
     * check exists the functions for the given hook 
     * @param string $hook 
     * @return boolean  
     */ 
    public static function exists_action($hook) 
    { 
        $hook=mb_strtolower($hook); 
        return (isset(self::$actions[$hook]))? TRUE:FALSE; 
    } 

}//end Class 
  
  
    /** 
     * Hooks Shortcuts not in class 
     */ 
    function add_action($hook,$function) 
    { 
        return Hook::add_action($hook,$function); 
    } 
  
    function do_action($hook) 
    { 
        return Hook::do_action($hook); 
    } 