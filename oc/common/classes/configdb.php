<?php defined('SYSPATH') or die('No direct script access.');
/**
* Extended functionality for Database Configuration
*
* @package    OC
* @category   Config
* @author     Chema <chema@open-classifieds.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/

class ConfigDB extends Config_Database {
 
    private static $data;//here we stored the config

    /**
     * construct for oc
     * @param array $config 
     */
    public function __construct()
    {        
        //loading the configs in the cache
        $this->load_config();
 
    }

    
    /**
     * loads a group of configs
     * @param type $group
     * @return array 
     */
    public function load($group)
    {                
        //not loaded so try to load, also if devel we refresh cache
        if(self::$data === NULL)
        {
            $this->load_config();
        }
        
        //check if we have it in cache
        if (isset(self::$data[$group]))
        {
            return self::$data[$group];
        }

        //not found
        return FALSE;
    }
    
    /**
     * 
     * Loads the configs from database to the cache
     * @return boolean
     */
    private function load_config()
    {
        //we don't read the config cache in development
        self::$data = (Kohana::$environment===Kohana::DEVELOPMENT)? NULL:Core::cache('config_db');
        
        //only load if empty
        if(self::$data === NULL)
        {
            // Load all the config data to cache
            $query = DB::select('group_name')
                        ->select('config_key')
                        ->select('config_value')
                        ->from($this->_table_name)
                        ->order_by('group_name','asc')
                        ->order_by('config_key','asc')
                        ->execute();
            $configs = $query->as_array();
            foreach($configs as $config)
            {
                self::$data[$config['group_name']][$config['config_key']]=$config['config_value'];
            }
           
            //caching all the results
            Core::cache('config_db', self::$data, 60*60*24);
            
            return TRUE;
        }
        else
        {
            //was already cached
            return FALSE;
        }
        
    }
    
    /**
     * 
     * Clears the config cache and loads it
     * @return boolean
     */
    public function reload_config()
    {
        // Clears cached data
        Core::cache('config_db',NULL,0);  
        //load config
        return $this->load_config();
    }
   
    
}//end class