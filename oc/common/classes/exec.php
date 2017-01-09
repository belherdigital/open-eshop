<?php 
/**
 * Executes php functions in background (asynchronous)
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Exec{   


    /**
     * Runs the function in backgroung
     * Called from our application, kohana is always loaded
     *
     * @param string $call_back function name to be called in background
     * @param array $params parameters that the call_back can recieve in order!
     * @param integer $priority for the system to do such task
     * @return integer PID
     */ 
    public static function background($call_back, $params = NULL, $priority = NULL)
    {
        if ( self::is_callable($call_back) ) //function exists so we can put it in background
        {
            //if (Kohana::$environment == Kohana::DEVELOPMENT)
                //return self::execute($call_back, base64_encode(serialize($params)) );

            $script = 'cli';

            //preparing the command to execute
            if (is_array($params))
            {
                $command = 'php -f '.APPPATH.$script.EXT.' '.$_SERVER['KOHANA_ENV'].' '.$call_back.' '.base64_encode(serialize($params)).'';
            }
            else 
            {
                //no params the -1 is because ::execute requires a value before the jobid if not in the cli won't work
                $command = 'php -f '.APPPATH.$script.EXT.' '.$_SERVER['KOHANA_ENV'].' '.$call_back.' -1';
            }
                   
            //priority check
            if($priority!==NULL)
            {
                $command = 'nohup nice -n '.$priority.' '.$command;
            }
            else
            {
                $command = 'nohup '.$command;
            }
            
            //adding output
            //$command = 'su - www-data -c "'.$command.' >/dev/null 2>/dev/null"';
            $command.= ' > /dev/null & echo $!';
            
            //execute
            //Log::instance()->add(LOG_DEBUG,'Exec->background - command: '.$command);
            //d($command);
            $pid = shell_exec($command); //$pid = shell_exec($command.' >/dev/null &');  //another way without priority
            
            //returning
            //Log::instance()->add(LOG_DEBUG,'Exec->background - Return value PID:'.$pid);
            return (int) $pid;
        
        }
        else //add error log
        {
            Log::instance()->add(LOG_ERR,'Exec->background - call_back not found - '.print_r($call_back,1));
            return FALSE;
        }
       
    }
    

     /**
      * executes the function and returns the function output
      * It's called from the CLI/ CLI-bootstrap, not usually from the app itself
      *
      * @param string  $call_back function name to execute
      * @param array   $params parameters that the call_back can recieve in order!
      */
    public static function execute($call_back, $params = NULL)
    {
        if ( self::is_callable($call_back) ) //function exists so we execute it
        {      
            if ($params!==NULL && $params!=-1)
            {
                //Log::instance()->add(LOG_DEBUG,'Exec->execute - params - '.$params);
                $params = unserialize(base64_decode($params));          
            }
            //d($params);
            //in case its a callback with parameters, be aware prams needs to be an array same order values as params in function
            if (is_array($params))
            {
                $return = call_user_func_array($call_back,$params);
            }
            //normal callback to a function no params
            else
            {       
                $return = call_user_func($call_back);
            }
                        
        }
        else  //add log
        {
            if (isset(Kohana::$log))
            {
                Kohana::$log->add(LOG_ERR,'Exec->execute - call_back not found - '.$call_back);
            }

        }
    }
    

   /**
    * Check if the Application running !
    *
    * @param      integer $pid process id from unix
    * @return     boolean
    */
    public static function is_running($pid)
    {
       exec('ps '.$pid, $process_state);
       return(count($process_state) >= 2);
    }
    
    /**
    * Kill Application PID
    *
    * @param  integer $pid process id from unix
    * @return boolean
    */
    public static function kill($pid)
    {
        if(exec::is_running($pid))
        {
            exec('kill -9 '.$pid);
            return TRUE;//killed
        }
        else
        {
            return FALSE;//not processing
        } 
    }


    /**
     * checks if a call_back function name can be used
     * @param string $call_back function name
     * @return boolean
     */
    public static function is_callable($call_back)
    {
        if (!is_string($call_back))
            return FALSE;
        
        if (function_exists($call_back))
        {
            return TRUE;
        }
        
        if (strpos($call_back, '::'))
        {
            $m=explode('::',$call_back);
            if (method_exists($m[0], $m[1]))
            {
                return TRUE;
            }
        } 
       
       return FALSE;
    
    }
   
}