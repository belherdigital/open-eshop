<?php defined('SYSPATH') or die('No direct script access.');
/**
 * mysqli class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, xavi <xavi@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */
class Database_MySQLi extends Kohana_Database_MySQLi {

    public function multi_query($sql, $as_object = FALSE, array $params = NULL)
    {
        // Make sure the database is connected
        $this->_connection or $this->connect();

        if ( ! empty($this->_config['profiling']))
        {
            // Benchmark this query for the current instance
            $benchmark = Profiler::start("Database ({$this->_instance})", $sql);
        }

        if ( ! empty($this->_config['connection']['persistent']) AND $this->_config['connection']['database'] !== Database_MySQLi::$_current_databases[$this->_connection_id])
        {
            // Select database on persistent connections
            $this->_select_db($this->_config['connection']['database']);
        }

        // Execute the query
        if (($result = $this->_connection->multi_query($sql)) === FALSE)
        {
            if (isset($benchmark))
            {
                // This benchmark is worthless
                Profiler::delete($benchmark);
            }

            throw new Database_Exception('[:code] :error ( :query )', array(
                ':code' => $this->_connection->errno,
                ':error' => $this->_connection->error,
                ':query' => $sql,
            ), $this->_connection->errno);
        }
        while($this->_connection->more_results() && $this->_connection->next_result()) {
            $extraResult = $this->_connection->use_result();
            if($extraResult instanceof mysqli_result){
                $extraResult->free();
            }
        }


        if (isset($benchmark))
        {
            Profiler::stop($benchmark);
        }

        // Set the last query
        $this->last_query = $sql;


        // Return the number of rows affected
        return $this->_connection->affected_rows;
        
    }

}