<?php defined('SYSPATH') or die('No direct script access.');

/*

TODO:
rename table
add and drop index/keys
table engine
table options
charset
...

*/


class Blacksmith_Table_Core {

	const IF_NOT_EXISTS = 'IF NOT EXISTS';

	const IF_EXISTS = 'IF EXISTS';

	protected $_sql = '';

	protected $_table_name;

	protected $_table_check;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct($table_name, $table_check = null)
	{
		$this->_table_name = $table_name;
		$this->_table_check = $table_check;
	}

	protected $_columns = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	protected function _compile($db)
	{
		$this->_sql .= ' TABLE';

		if ($this->_table_check !== null)
		{
			$this->_sql .= ' ' . $this->_table_check;
		}

		$this->_sql .= ' `' . $this->_table_name . '`';

		if ($this->_drop_column)
		{
			$this->_sql .= " DROP COLUMN `$this->_drop_column`";
		}
		else if ($this->_add_column)
		{
			$this->_sql .= ' ADD COLUMN';
		}
		else if ($this->_modify_column)
		{
			$this->_sql .= ' MODIFY COLUMN ' . $this->_column->sql($db);
		}

		if ((!$this->_drop_column or !$this->_modify_column) and count($this->_columns) > 0)
		{
			$this->_sql .= ' (';

			$columns_sql = array();

			foreach ($this->_columns as $column) 
			{
				$columns_sql[] = $column->sql($db);
			}

			$this->_sql .= implode(', ', $columns_sql);

			if ($this->_primary_key)
			{
				$this->_sql .= ", PRIMARY KEY (`$this->_primary_key`)";
			}

			$this->_sql .= ')';
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



	protected $_add_column = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function add_column()
	{
		$this->_add_column = true;
		return $this;
	}

	protected $_modify_column = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function modify_column()
	{
		$this->_modify_column = true;
		return $this;
	}

	protected $_drop_column;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function drop_column($column_name)
	{
		$this->_drop_column = $column_name;
		return $this;
	}

	protected $_primary_key;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function primary_key($column_name)
	{
		$this->_primary_key = $column_name;
		return $this;
	}

	//column definitions

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	protected function _column($column_name)
	{
		$column = Blacksmith_Column::factory($column_name);
		
		if ($this->_modify_column)
		{
			$this->_column = $column;
		}
		else
		{
			$this->_columns[] = $column;
		}		

		return $column;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function increments($column_name)
	{
		$this->primary_key($column_name);
		return $this->_column($column_name)->auto_increment();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function string($column_name, $length = 100)
	{
		return $this->_column($column_name)->type("VARCHAR", $length);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function tiny_text($column_name)
	{
		return $this->_column($column_name)->type("TINYTEXT");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function text($column_name)
	{
		return $this->_column($column_name)->type("TEXT");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function medium_text($column_name)
	{
		return $this->_column($column_name)->type("MEDIUMTEXT");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function long_text($column_name)
	{
		return $this->_column($column_name)->type("LONGTEXT");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function tiny_int($column_name, $length = 11)
	{
		return $this->_column($column_name)->type("TINYINT", $length);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function small_int($column_name, $length = 11)
	{
		return $this->_column($column_name)->type("SMALLINT", $length);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function medium_int($column_name, $length = 11)
	{
		return $this->_column($column_name)->type("MEDIUMINT", $length);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function int($column_name, $length = 11)
	{
		return $this->_column($column_name)->type("INT", $length);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function big_int($column_name, $length = 11)
	{
		return $this->_column($column_name)->type("BIGINT", $length);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function float($column_name, $length = 10, $decimals = 2)
	{
		return $this->_column($column_name)->type("FLOAT", $length, $decimals);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function decimal($column_name, $length = 10, $decimals = 2)
	{
		return $this->_column($column_name)->type("DECIMAL", $length, $decimals);
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function tiny_blob($column_name)
	{
		return $this->_column($column_name)->type("TINYBLOB");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function blob($column_name)
	{
		return $this->_column($column_name)->type("BLOB");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function medium_blob($column_name)
	{
		return $this->_column($column_name)->type("MEDIUMBLOB");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function long_blob($column_name)
	{
		return $this->_column($column_name)->type("LONGBLOB");
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boolean($column_name)
	{
		return $this->_column($column_name)->type("INT", 1);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function date($column_name)
	{
		return $this->_column($column_name)->type("DATE");	
	}

}