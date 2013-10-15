<?php defined('SYSPATH') or die('No direct script access.');

class Blacksmith_Core {

	const CREATE = 'CREATE';

	const ALTER = 'ALTER';

	const DROP = 'DROP';

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function create()
	{
		return new self(Blacksmith::CREATE);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function alter()
	{
		return new self(Blacksmith::ALTER);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function drop()
	{
		return new self(Blacksmith::DROP);
	}

	protected $_sql;
	
	protected $_type;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($type)
	{
		$this->_type = $type;
	}

	protected $_table;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function table($if_not_exists, $table_name = null)
	{
		if ($table_name === null)
		{
			$table_name = $if_not_exists;
			$if_not_exists = null;
		}

		return $this->_table = new Blacksmith_Table($table_name, $if_not_exists);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	protected function _compile($db)
	{
		$this->_sql = $this->_type;

		if ($this->_table instanceof Blacksmith_Table)
		{
			$this->_sql .= $this->_table->sql($db);	
		}	
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function sql($db = null)
	{
		if ($db === null)
		{
			$db = Database::instance();
		}

		$this->_compile($db);

		return $this->_sql;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function forge($db = null)
	{
		$this->sql($db);

		$db->query(null, $this->_sql);
	}

}