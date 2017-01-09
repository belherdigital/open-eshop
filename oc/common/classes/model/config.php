<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Config extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'config';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'config_key';

    
    /**
     * Insert a new object to the database
     * @param  Validation $validation Validation object
     * @return ORM
     */
    public function create(Validation $validation = NULL)
    {
        parent::create($validation);
        $this->reload_config();
    }

    /**
     * Updates a single record or multiple records
     *
     * @chainable
     * @param  Validation $validation Validation object
     * @return ORM
     */
    public function update(Validation $validation = NULL)
    {
        parent::update($validation);
        $this->reload_config();
    }

    /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @return ORM
     */
    public function delete()
    {
        parent::delete();
        $this->reload_config();
    }


    public function form_setup($form)
    {
        // $form->fields['group_name']['display_as'] = 'text';
        // $form->fields['config_key']['display_as'] = 'text';
    }

    public function exclude_fields()
    {
        //return array('id_user', 'salt', 'date_created', 'date_lastlogin', 'ip_created', 'ip_lastlogin');
    }

    /**
     * everytime we save the config we relad the cache
     * @return boolean 
     */
    public function reload_config()
    {
        $c = new ConfigDB(); 
        return $c->reload_config();
    }

    /**
     * is used to create configs if they dont exist
     * @param array
     * @return boolean 
     */
    public static function config_array($configs)
    {
        $return = FALSE;
        foreach ($configs as $c => $value) 
        {
            // get config from DB
            $confp = new self();
            $confp->where('config_key','=',$value['config_key'])
                  //->where('group_name','=',$value['group_name'])
                  ->limit(1)->find();

            // if do not exist (not loaded) create them, else do nothing
            if (!$confp->loaded())
            {
                $confp->config_key = $value['config_key'];
                $confp->group_name = $value['group_name'];
                $confp->config_value = $value['config_value'];
                $confp->save();

                $return = TRUE;
            }
        }   

        return $return;
    }

    /**
     * sets the value for 1 key
     * @param [type] $group_name [description]
     * @param [type] $config_key [description]
     * @param [type] $value      [description]
     */
    public static function set_value($group_name,$config_key,$value)
    {
        $confp = new self();
        $confp->where('config_key','=',$config_key)
              ->where('group_name','=',$group_name)
              ->limit(1)->find();

        // if do not exist (not loaded) create
        if (!$confp->loaded())
        {
            $confp->config_key = $config_key;
            $confp->group_name = $group_name;    
        }
        
        $confp->config_value = $value;
        $confp->save();
    }

    protected $_table_columns =    
array (
  'group_name' => 
  array (
    'type' => 'string',
    'column_name' => 'group_name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'character_maximum_length' => '128',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'config_key' => 
  array (
    'type' => 'string',
    'column_name' => 'config_key',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '128',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'config_value' => 
  array (
    'type' => 'string',
    'character_maximum_length' => '65535',
    'column_name' => 'config_value',
    'column_default' => NULL,
    'data_type' => 'text',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

} // END Model_Config