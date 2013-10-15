<?php defined('SYSPATH') or die('No direct access allowed!'); 
  
class DateTest extends Kohana_UnitTest_TestCase 
{ 

    public function provider_format()
    {
        return array(
            array('d-m-Y','28 April 1984','28-04-1984'),
            array('d/m/Y','28 April 1984','28/04/1984'),
            array('d/m/Y','now',date('d/m/Y')),
        );
    }

    /**
     * @dataProvider provider_format
     */
    public function test_format($format,$date,$result) 
    {
        $this->assertEquals(Date::format($date,$format),$result); 
    } 



    public function provider_unix2mysql()
    {
        return array(
            array(time(),date('Y-m-d H:i:s')),
            array(strtotime('1 January 1970'),'1970-01-01 00:00:00'),
            array(451951200,'1984-04-28 00:00:00'),
        );
    }

    /**
     * @dataProvider provider_unix2mysql
     */
    public function test_unix2mysql($date,$result) 
    {
        $this->assertEquals(Date::unix2mysql($date),$result); 
    } 

    /**
     * @dataProvider provider_unix2mysql
     */
    public function test_mysql2unix($time,$mysql) 
    {
        $this->assertEquals(Date::mysql2unix($mysql),$time); 
    } 



    public function provider_range()
    {
        return array(

            array( array (0 => '2013-06-23',1 => '2013-06-24',2 => '2013-06-25',3 => '2013-06-26',4 => '2013-06-27',5 => '2013-06-28',6 => '2013-06-29',7 => '2013-06-30',),
                    '2013-06-23','2013-06-30'),
            array( array (0 => '2013-06-23',1 => '2013-06-25',2 => '2013-06-27',3 => '2013-06-29'),
                    '2013-06-23','2013-06-30','+2 day'),
            array( array (0 => '23/06 2013',1 => '26/06 2013',2 => '29/06 2013'),
                    '2013-06-23','2013-06-30','+3 day','d/m Y'),
            array( array (0 =>array('fecha' => '06 2013','count' => 0),1=>array('fecha'=>'06 2013','count' => 0)),
                    '2013-06-23','2013-06-26','+3 day','m Y',array('fecha'=>0,'count'=> 0),'fecha'),
        );
    }

    /**
     * @dataProvider provider_range
     */
    public function test_range($result, $start, $end, $step = '+1 day', $format = 'Y-m-d', $array_fill = NULL, $d_field = 'date') 
    {
        $this->assertEquals(Date::range($start, $end, $step , $format , $array_fill , $d_field),$result); 
    } 



    public function provider_secs_to_time()
    {
        return array(
            array(((500*24)*60*60)+(37*60)+5,'12000:37:05'),
            array((11*60*60)+(25*60)+30,'11:25:30'),
            array((59*60)+59,'00:59:59'),
            array(25,'00:00:25'),
        );
    }

    /**
     * @dataProvider provider_secs_to_time
     */
    public function test_secs_to_time($secs,$result) 
    {
        $this->assertEquals(Date::secs_to_time($secs),$result); 
    } 


}