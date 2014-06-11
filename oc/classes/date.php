<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date helper class
 *
* @package    OC
* @category   Date
* @author     Chema <chema@open-classifieds.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/
class Date extends Kohana_Date {
    
    /**
     * Formats the given date into another format
     * 
     * @param string $date
     * @param string $format
     * @return string
     */
	public static function format($date, $format='d/m/Y')
	{
	   return date($format, strtotime($date));
	}
	
	/**
	 * 
	 * Converts a Unix time stamp to a MySQL date
	 * @param integer $date
	 * @return string
	 */
	public static function unix2mysql($date = NULL)
	{
		if($date === NULL)
		{
			$date = time();
		}
	    return date(Date::$timestamp_format,$date);
	}
	
	/**
	 * 
	 * Converts a MySQL date to a Unix date
	 * @param unknown_type $date
	 * @return number
	 */
	public static function mysql2unix($date)
	{
	    return strtotime($date);
	}

    /**
     * shortcut fot DateTime::createFromFormat
     * @param  string $date          
     * @param  string $input_format  
     * @param  string $output_format 
     * @return mixed                
     */
    public static function from_format($date, $input_format = 'd/m/yy', $output_format = 'm-d-Y')
    {
        if($date === NULL)
            $date = time();

        $datetime = DateTime::createFromFormat($input_format, $date);

        switch ($output_format) 
        {
            case 'unix':
                return date::unix2mysql($datetime->getTimestamp());
                break;
            
            default:
                return $datetime->format($output_format);
                break;
        }

    }

    /**
     * get an array range with dates in a specific format
     * @param  string $start      from
     * @param  string $end        to
     * @param  string $step       strtotime style
     * @param  string $format     date format
     * @param  array $array_fill default fill for the array to return
     * @param  string $d_field      the field we use to put the date into
     * @return array             
     */
    public static function range($start, $end, $step = '+1 day', $format = 'Y-m-d', $array_fill = NULL, $d_field = 'date') 
    {
        //return array
        $range = array();

        if (is_string($start) === TRUE) 
            $start = strtotime($start);
        if (is_string($end) === TRUE) 
            $end   = strtotime($end);

        do 
        {
            $date = date($format, $start);

            if (is_array($array_fill))
            {
                $array_fill[$d_field]  = $date;
                $range[] = $array_fill;
            }   
            else
                $range[] = $date;
            
            $start  = strtotime($step, $start);//increase

        } while($start <= $end);

        return $range;
    }
    

    /**
     * seconds to readable time format h:i:s
     * @param  integer $secs 
     * @return string      
     */
    public static function secs_to_time($secs) 
    {
        $times = array(3600, 60, 1);
        $time = '';
        $tmp  = '';

        for($i = 0; $i < 3; $i++) 
        {
            $tmp = floor($secs / $times[$i]);
            if($tmp < 1) 
            {
                $tmp = '00';
            }
            elseif($tmp < 10) 
            {
                $tmp = '0' . $tmp;
            }
            $time .= $tmp;
            if($i < 2) 
            {
                $time .= ':';
            }
            $secs = $secs % $times[$i];
        }
        return $time;
    }
	
    /**
     * returns timezones ins a more friendly array way, ex Madrid [+1:00]
     * @return array 
     */
    public static function get_timezones()
    {
        if (method_exists('DateTimeZone','listIdentifiers'))
        {
            $utc = new DateTimeZone('UTC');
            $dt  = new DateTime('now', $utc);

            $timezones = array();
            $timezone_identifiers = DateTimeZone::listIdentifiers();

            foreach( $timezone_identifiers as $value )
            {
                $current_tz = new DateTimeZone($value);
                $offset     =  $current_tz->getOffset($dt);

                if ( preg_match( '/^(America|Antartica|Africa|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)\//', $value ) )
                {
                    $ex=explode('/',$value);//obtain continent,city
                    $city = isset($ex[2])? $ex[1].' - '.$ex[2]:$ex[1];//in case a timezone has more than one
                    $timezones[$ex[0]][$value] = $city.' ['.Date::format_offset($offset).']';
                }
            }
            return $timezones;
        }
        else//old php version
        {
            return FALSE;
        }
    }

    /**
     * gets the offset of a date
     * @param  string $offset 
     * @return string       
     */
    public static function format_offset($offset) 
    {
            $hours = $offset / 3600;
            $remainder = $offset % 3600;
            $sign = $hours > 0 ? '+' : '-';
            $hour = (int) abs($hours);
            $minutes = (int) abs($remainder / 60);

            if ($hour == 0 AND $minutes == 0) {
                $sign = ' ';
            }
            return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');
    }
   
    
} // End Date