<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for ORM
 *
 * @package    OC
 * @category   Model
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class ORM extends Kohana_ORM {

	/**
	 * Name of the database to use
	 *
	 * @access	protected
	 * @var		string	$_db default [default]
	 */
	protected $_db = 'default';


	/**
	 * 
	 * formo definitions
	 * 
	 */
	public function form_setup($form){}

    /**
     * Insert a new object to the database - Overwrite!
     * @param  Validation $validation Validation object
     * @throws Kohana_Exception
     * @return ORM
     */
    public function create(Validation $validation = NULL)
    {
        //Hack to use the PHP date time instead of the MySQL for created
        $cols = $this->list_columns();
        
        // the column created exists and we didnt pass any value before
        if (isset($cols['created']) AND !array_key_exists('created', $this->_changed))
        {
            //add the value, forcing it so wont use the DB default ;)
            $this->set('created',Date::unix2mysql());
        }

        return parent::create($validation);
    }
}