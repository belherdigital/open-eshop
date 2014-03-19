<?php defined('SYSPATH') or die('No direct script access.');
/**
 * MySQLi database result.
 *
 * @package    Kohana/Database
 * @category   Query/Result
 * @author     Azuka Okuleye
 * @copyright  (c) Anybody
 * @license    http://example.com/license
 */
class Kohana_Database_MySQLi_Result extends Database_Result {

	protected $_internal_row = 0;

	public function __construct($result, $sql, $as_object)
	{
		parent::__construct($result, $sql, $as_object);

		// Find the number of rows in the result
		$this->_total_rows = $result->num_rows;
	}

	public function __destruct()
	{
		if ($this->_result instanceof MySQLi_Result)
		{
			$this->_result->free();
		}
	}

	public function seek($offset)
	{
		if ($this->offsetExists($offset) AND $this->_result->data_seek($offset))
		{
			// Set the current row to the offset
			$this->_current_row = $this->_internal_row = $offset;

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function current()
	{
		if ($this->_current_row !== $this->_internal_row AND ! $this->seek($this->_current_row))
			return FALSE;

		// Increment internal row for optimization assuming rows are fetched in order
		$this->_internal_row++;

		if ($this->_as_object === TRUE)
		{
			// Return an stdClass
			return $this->_result->fetch_object();
		}
		elseif (is_string($this->_as_object))
		{
			// Return an object of given class name
			return $this->_result->fetch_object($this->_as_object);
		}
		else
		{
			// Return an array of the row
			return $this->_result->fetch_assoc();
		}
	}

} // End Database_$this->_connection->Result_Select
