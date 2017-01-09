<?php
/**
* Number helper class. Provides additional formatting methods that for working
* with numbers.
*
* @package    Kohana
* @category   Helpers
* @author     Oliver <oliver@open-classifieds.com>
* @copyright  (c) 2009-2015 Open Classifieds Team
* @license    GPL v3
*/


class Num extends Kohana_Num {

    /**
     * returns the precentage change between to values
     * @param  float $new_value 
     * @param  float $old_value 
     * @param  integer $precision 
     * @param  bool $format 
     * @return string
     */
    public static function percent_change($new_value, $old_value, $precision = 0, $format = FALSE)
    {
        if ($old_value == 0)
            return '--';

        $ret = $new_value - $old_value;

        $ret = ($ret / $old_value) * 100;

        $ret = self::round($ret, $precision);

        if ($format === TRUE)
            $ret = self::format($ret, $precision);

        return $ret.'%';

    }

}

