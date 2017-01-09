<?php defined('SYSPATH') or die('No direct script access.');

class Cache_File extends Kohana_Cache_File {


    /**
     * Overwritten I do not want to throw an exception...just ignore it! return default and delete.
     * Retrieve a cached value entry by id. 
     *
     *     // Retrieve cache entry from file group
     *     $data = Cache::instance('file')->get('foo');
     *
     *     // Retrieve cache entry from file group and return 'bar' if miss
     *     $data = Cache::instance('file')->get('foo', 'bar');
     *
     * @param   string   $id       id of cache to entry
     * @param   string   $default  default value to return if cache miss
     * @return  mixed
     * @throws  Cache_Exception
     */
    public function get($id, $default = NULL)
    {
        $filename = Cache_File::filename($this->_sanitize_id($id));
        $directory = $this->_resolve_directory($filename);

        // Wrap operations in try/catch to return default
        try
        {
            // Open file
            $file = new SplFileInfo($directory.$filename);

            // If file does not exist
            if ( ! $file->isFile())
            {
                // Return default value
                return $default;
            }
            else
            {
                // Open the file and parse data
                $created  = $file->getMTime();
                $data     = $file->openFile();
                $lifetime = $data->fgets();

                // If we're at the EOF at this point, corrupted!
                if ($data->eof())
                {
                    $this->_delete_file($file, NULL, TRUE);
                    return $default;
                }

                $cache = '';

                while ($data->eof() === FALSE)
                {
                    $cache .= $data->fgets();
                }

                // Test the expiry
                if (($created + (int) $lifetime) < time())
                {
                    // Delete the file
                    $this->_delete_file($file, NULL, TRUE);
                    return $default;
                }
                else
                {
                    return unserialize($cache);
                }
            }

        }
        catch (ErrorException $e)
        {
            $this->_delete_file($file, NULL, TRUE);
            return $default;
        }
    }


}