<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User Roles
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_OC_Role extends ORM {

    /**
     * user roles
     */
    const ROLE_USER             = 1;
    const ROLE_TRANSLATOR       = 5;
    const ROLE_MODERATOR        = 7;
    const ROLE_ADMIN            = 10;
    
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'roles';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_role';

    protected $_has_many = array(
        'access' => array(
            'model'   => 'access',
            'foreign_key' => 'id_role',
        ),
    );

    public function form_setup($form)
    {
        $form->fields['description']['display_as'] = 'textarea';
    }

    public function exclude_fields()
    {
        return array('date_created');
    }

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
        return array('id_role'      => array(array('numeric')),
                    'name'          => array(   array(array($this, 'unique'), array('name', ':value')),
                                                array('not_empty'),
                                                array('max_length', array(':value', 45)), 
                                            ),
                    );
    }

    protected $_table_columns =  
    array (
      'id_role' => 
      array (
        'type' => 'int',
        'min' => '0',
        'max' => '4294967295',
        'column_name' => 'id_role',
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
      'name' => 
      array (
        'type' => 'string',
        'column_name' => 'name',
        'column_default' => NULL,
        'data_type' => 'varchar',
        'is_nullable' => true,
        'ordinal_position' => 2,
        'character_maximum_length' => '45',
        'collation_name' => 'utf8_general_ci',
        'comment' => '',
        'extra' => '',
        'key' => 'UNI',
        'privileges' => 'select,insert,update,references',
      ),
      'description' => 
      array (
        'type' => 'string',
        'column_name' => 'description',
        'column_default' => NULL,
        'data_type' => 'varchar',
        'is_nullable' => true,
        'ordinal_position' => 3,
        'character_maximum_length' => '245',
        'collation_name' => 'utf8_general_ci',
        'comment' => '',
        'extra' => '',
        'key' => '',
        'privileges' => 'select,insert,update,references',
      ),
      'date_created' => 
      array (
        'type' => 'string',
        'column_name' => 'date_created',
        'column_default' => 'CURRENT_TIMESTAMP',
        'data_type' => 'timestamp',
        'is_nullable' => false,
        'ordinal_position' => 4,
        'comment' => '',
        'extra' => '',
        'key' => '',
        'privileges' => 'select,insert,update,references',
      ),
    );
}