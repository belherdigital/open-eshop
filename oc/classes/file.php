<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Kohana File
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class File extends Kohana_File{
    
    /**
     * copies files/directories recursively
     * @param  string  $source    from
     * @param  string  $dest      to
     * @param  boolean $overwrite overwrite existing file
     * @return void             
     */
    public static function copy($source, $dest, $overwrite = false)
    { 
        //Lets just make sure our new folder is already created. Alright so its not efficient to check each time... bite me
        if(is_file($dest))
        {
            copy($source, $dest);
            return;
        }
        
        if(!is_dir($dest))
            mkdir($dest);

             

        $objects = scandir($source);
        foreach ($objects as $object) 
        {
            if($object != '.' && $object != '..')
            { 
                $path = $source . '/' . $object; 
                if(is_file( $path))
                { 
                    if(!is_file( $dest . '/' . $object) || $overwrite) 
                    {
                        if(!@copy( $path,  $dest . '/' . $object))
                            die('File ('.$path.') could not be copied, likely a permissions problem.'); 
                    }
                }
                elseif(is_dir( $path))
                { 
                    if(!is_dir( $dest . '/' . $object)) 
                        mkdir( $dest . '/' . $object); // make subdirectory before subdirectory is copied 

                    File::copy($path, $dest . '/' . $object, $overwrite); //recurse! 
                }
                 
            } 
        } 
        
    }

    /**
     * deletes file or directory recursevely
     * @param  string $file 
     * @return void       
     */
    public static function delete($file)
    {
        if (is_dir($file)) 
        {
            $objects = scandir($file);
            foreach ($objects as $object) 
            {
                if ($object != '.' && $object != '..') 
                {
                    if (is_dir($file.'/'.$object)) 
                        File::delete($file.'/'.$object); 
                    else 
                        unlink($file.'/'.$object);
                }
            }
            reset($objects);
            @rmdir($file);
        }
        elseif(is_file($file))
            unlink($file);
    }


    /**
     * write to file
     * @param $filename fullpath file name
     * @param $content
     * @return boolean
     */
    public static function write($filename,$content)
    {
        $file = fopen($filename, 'w');
        if ($file)
        {//able to create the file
            fwrite($file, $content);
            fclose($file);
            return TRUE;
        }
        return FALSE;   
    }
    
    /**
     * read file content
     * @param $filename fullpath file name
     * @return $string or false if not found
     */
    public static function read($filename)
    {
        if (is_readable($filename))
        {
            $file = fopen($filename, 'r');
            if ($file)
            {//able to read the file
                $data = fread($file, filesize($filename));
                fclose($file);
                return $data;
            }
        }
        return FALSE;   
    }

}
