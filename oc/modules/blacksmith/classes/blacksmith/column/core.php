<?php defined('SYSPATH') or die('No direct script access.');


class Blacksmith_Column_Core {

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public static function factory($column_name)
	{
		return new Blacksmith_Column($column_name);
	}

	protected $_sql = '';	

	protected $_column_name = '';

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($column_name)
	{
		$this->_column_name = $column_name;
	}

	protected $_type = '';

	protected $_length = '';

	protected $_decimals = '';

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function type($type, $length = null, $decimals = null)
	{
		$this->_type = $type;

		$this->_length = $length;

		$this->_decimals = $decimals;

		return $this;
	}


	protected $_not_null = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function not_null()
	{
		$this->_not_null = true;
		return $this;
	}


	protected $_auto_increment = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function auto_increment()
	{
		$this->_type = 'INT';
		$this->_length = 11;
		$this->_not_null = true;
		$this->_unsigned = true;
		$this->_auto_increment = true;
		return $this;
	}

	protected $_default_value;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function default_value($value)
	{
		$this->_default_value = $value;
		return $this;
	}

	protected $_unsigned = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function unsigned($unsigned)
	{
		$this->_unsigned = (bool) $unsigned;
		return $this;
	}

	protected $_binary = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function binary($binary)
	{
		$this->_binary = (bool) $binary;
		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	protected function _compile($db)
	{
		$this->_sql .= '`' . $this->_column_name . '`';

		$this->_sql .= ' ' . $this->_type;

		if ($this->_length !== null)
		{
			$this->_sql .= "(";

			$this->_sql .= $this->_length;

			if ($this->_decimals)
			{
				$this->_sql .= ",{$this->_decimals}";
			}

			$this->_sql .= ")";
		}

		if (in_array($this->_type, array('TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT', 'FLOAT', 'DECIMAL')) and $this->_unsigned)
		{
			$this->_sql .= ' UNSIGNED';
		}

		if (in_array($this->_type, array('TINYTEXT', 'TEXT', 'MEDIUMTEXT', 'LONGTEXT')) and $this->_binary)
		{
			$this->_sql .= ' BINARY';
		}

		if ($this->_not_null)
		{
			$this->_sql .= ' NOT NULL';
		}

		if ($this->_default_value !== null)
		{
			$this->_sql .= ' DEFAULT ' . $db->escape($this->_default_value);
		}

		if ($this->_auto_increment)
		{
			$this->_sql .= ' AUTO_INCREMENT';	
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function sql($db)
	{
		$this->_compile($db);

		return $this->_sql;
	}
}