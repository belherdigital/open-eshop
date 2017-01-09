<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  menus
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Menu {

   
    public static function get()    {
        $menus = json_decode(core::config('general.menu'),TRUE);
        if (!is_array($menus))
            $menus = Array();

        return $menus;
    }

    /**
     * menus have this items
     * 
     * [0] => array('title'=>'name',
     *               'URL'=>URL,  
     *               'target'=>blank,
     *               'icon'=>'icon',
     *                 )
     */
    public static function add($title, $url, $target = '', $icon=NULL)
    {
        $menus = self::get();
        //d($menus);
        $menus[] = array(   'title' => $title,
                            'url'       => $url,
                            'target'    => $target,
                            'icon'     => $icon,
                        );
        return self::save($menus);

    }

    public static function delete($key)
    {
        $menus = self::get();
        unset($menus[$key]);
        self::save($menus);
    }


    public static function save($items)
    {
        // save widget to DB
        $conf = new Model_Config();
        $conf->where('group_name','=','general')
                    ->where('config_key','=','menu')
                    ->limit(1)->find();
        if (!$conf->loaded())
        {
            $conf->group_name = 'general';
            $conf->config_key = 'menu';
        }
        
        $conf->config_value = json_encode($items);
        try
        {
            $conf->save();
            return TRUE;
        }
        catch (Exception $e)
        {
            throw HTTP_Exception::factory(500,$e->getMessage());     
        }
        return FALSE;

    }

    /**
     * changes the order to display menus
     * @param  array  $order 
     * @return bool
     */
    public static function change_order(array $order)
    {        
        $menus = self::get();

        $new_menus =  array();

        //using order they send us
        foreach ($order as $key)
        {
            if (isset($menus[$key]))
                $new_menus[$key] = $menus[$key];
        } 
            
       
        return self::save($new_menus);
    }

    /**
     * get values for a menu item
     * @param  string $name 
     * @return array/bool    
     */
    public static function get_item($name)
    {
        $menus = self::get();
        
		return $menus[$name];
    }

    /**
     * menus have this items
     * 
     * [0] => array('title'=>'name',
     *               'URL'=>URL,  
     *               'target'=>blank,
     *               'icon'=>'icon',
     *                 )
     */
    public static function update($name, $title, $url, $target = '', $icon=NULL)
    {
        $menus = self::get();
        //d($menus);
        $menus[$name] = array(   'title' => $title,
                            'url'       => $url,
                            'target'    => $target,
                            'icon'     => $icon,
                        );
        return self::save($menus);

    }

}
