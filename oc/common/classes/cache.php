<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cache extends Kohana_Cache {


    /**
     * Replaces troublesome characters with underscores.
     *
     *     // Sanitize a cache id
     *     $id = $this->_sanitize_id($id);
     *
     * @param   string  $id  id of cache to sanitize
     * @return  string
     */
    protected function _sanitize_id($id)
    {
        return sha1($id);
    }
}