<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controllers user access
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Access extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'access';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_access';

    
    /**
     * get all the controllers and the actions that can be used
     * @return array 
     */
    public static function list_controllers()
    {
        $list_controllers = array();

        $controllers = Kohana::list_files('classes/controller/panel');

        foreach ($controllers as $controller) 
        {
            if (is_array($controller))
            {
                foreach ($controller as $c) 
                {
                    $c = basename($c,'.php');
                    $list_controllers[$c] = self::get_action_methods($c);
                }
            }
            else
            {
                $controller = basename($controller,'.php');
                $list_controllers[$controller] = self::get_action_methods($controller);
            }
        }

        return $list_controllers;
    }
    
    /**
     * gets the actions from the controller panel of the desire controller
     * @param  string $controller 
     * @return array
     */
    private static function get_action_methods($controller)
    {
        $methods_list = array();
        $class = 'Controller_Panel_'.$controller;

        if (class_exists($class))
        {
            $class      = new ReflectionClass($class);
            $methods    = $class->getMethods();
            foreach ($methods as $obj => $val) 
            {
                if (strpos( $val->name , 'action_') !== FALSE )
                {
                    $methods_list[] = str_replace('action_', '', $val->name);
                }
            }
        }

        return $methods_list;
    }

    public function form_setup($form)
    {
       
    }

    public function exclude_fields()
    {
    
    }


 protected $_table_columns =     
array (
  'id_access' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_access',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'id_role' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_role',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'access' => 
  array (
    'type' => 'string',
    'column_name' => 'access',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'character_maximum_length' => '100',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

}