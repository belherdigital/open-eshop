<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OC statistics!
 *
 * @package    OC
 * @category   Stats
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Stats extends Auth_Controller {


    public function action_index()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));

        $this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/oc-panel/stats/dashboard.js');
        
        $this->template->title = __('Stats');
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/dashboard');

        //Getting the dates and range
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        //mysql formated dates
        $my_from_date = Date::unix2mysql($from_date);
        $my_to_date   = Date::unix2mysql($to_date);

        //dates range we are filtering
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;


        /////////////////////VISITS STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $visits = $query->as_array('date');


        $stats_daily = array();
        foreach ($dates as $date) 
        {
            $count_views = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;            
            $stats_daily[] = array('date'=>$date['date'],'views'=> $count_views);
        } 

        $content->stats_daily =  $stats_daily;


         //Today 
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_today     = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_yesterday= (isset($ads[0]['count']))?$ads[0]['count']:0;


        //Last 30 days visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;


        /////////////////////ORDERS STATS////////////////////////////////

        //orders created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $orders = $query->as_array('date');

        $stats_orders = array();
        foreach ($dates as $date) 
        {
            $count_orders = (isset($orders[$date['date']]['count']))?$orders[$date['date']]['count']:0;
            $count_sum = (isset($orders[$date['date']]['total']))?$orders[$date['date']]['total']:0;
            
            $stats_orders[] = array('date'=>$date['date'],'#orders'=> $count_orders,'$'=>$count_sum);
        } 
        $content->stats_orders =  $stats_orders;


         //Today 
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->orders_yesterday     = (isset($ads[0]['count']))?$ads[0]['count']:0;
        $content->amount_yesterday     = (isset($ads[0]['total']))?$ads[0]['total']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->orders_today = (isset($ads[0]['count']))?$ads[0]['count']:0;
        $content->amount_today = (isset($ads[0]['total']))?$ads[0]['total']:0;


        //Last 30 days orders
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->execute();

        $orders = $query->as_array();
        $content->orders_month = (isset($orders[0]['count']))?$orders[0]['count']:0;
        $content->amount_month = (isset($orders[0]['total']))?$orders[0]['total']:0;

        //total orders
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->execute();

        $orders = $query->as_array();
        $content->orders_total = (isset($orders[0]['count']))?$orders[0]['count']:0;
        $content->amount_total = (isset($orders[0]['total']))?$orders[0]['total']:0;
        
    }




}