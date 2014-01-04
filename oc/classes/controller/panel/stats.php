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

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats'))->set_url(Route::url('oc-panel',array('controller'  => 'stats'))));
        $this->template->title = __('Stats');

        $this->template->styles = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/stats/dashboard.js');
        
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/dashboard');
        $content->title = $this->template->title;

        //stats by product
        $content->product = NULL;
        if ($this->request->param('id'))
        {
            $product = new Model_product();
            $product->where('seotitle','=',$this->request->param('id'))
                ->limit(1)->find();
            if ($product->loaded())
            {
                $content->product = $product;
                $this->template->title.=' '.$product->title;
                $content->title.=' '.$product->title;
                Breadcrumbs::add(Breadcrumb::factory()->set_title($product->title));
            }
        }
        

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

        //dates range we are filtering, 1 year back from the to date.
        $dates_year     = Date::range($from_date-(365*24*60*60),$to_date,'+1 month','Y-m',array('date'=>0,'count'=> 0),'date');

        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;


        /////////////////////VISITS STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array($my_from_date,$my_to_date));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('DATE( created )'))
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
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_today     = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_yesterday= (isset($ads[0]['count']))?$ads[0]['count']:0;


        //Last 30 days visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->execute();

        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits');
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;



        //visits by month 1 year from to_date
        $query = DB::select(DB::expr('CONCAT(YEAR(`created`),"-",MONTH(`created`)) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits');
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);

        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->execute();
        $visits = $query->as_array('date');
        $stats_by_month = array();

        foreach ($dates_year as $date) 
        {
            $count_views = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;            
            $stats_by_month[] = array('date'=>$date['date'],'views'=> $count_views);
        } 

        $content->stats_by_month =  $stats_by_month;

        /////////////////////ORDERS STATS////////////////////////////////

        //orders created last XX days
        $query = DB::select(DB::expr('DATE(pay_date) date'))
                        ->select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('pay_date','between',array($my_from_date,$my_to_date))
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->group_by(DB::expr('DATE( pay_date )'))
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
                        ->where(DB::expr('DATE( pay_date )'),'=',DB::expr('CURDATE()'))
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->group_by(DB::expr('DATE( pay_date )'))
                        ->order_by('pay_date','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->orders_today = (isset($ads[0]['count']))?$ads[0]['count']:0;
        $content->amount_today = (isset($ads[0]['total']))?$ads[0]['total']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where(DB::expr('DATE( pay_date )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->group_by(DB::expr('DATE( pay_date )'))
                        ->order_by('pay_date','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->orders_yesterday     = (isset($ads[0]['count']))?$ads[0]['count']:0;
        $content->amount_yesterday     = (isset($ads[0]['total']))?$ads[0]['total']:0;


        //Last 30 days orders
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('pay_date','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->execute();

        $orders = $query->as_array();
        $content->orders_month = (isset($orders[0]['count']))?$orders[0]['count']:0;
        $content->amount_month = (isset($orders[0]['total']))?$orders[0]['total']:0;

        //total orders
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->execute();

        $orders = $query->as_array();
        $content->orders_total = (isset($orders[0]['count']))?$orders[0]['count']:0;
        $content->amount_total = (isset($orders[0]['total']))?$orders[0]['total']:0;



        //orders per month
        $query = DB::select(DB::expr('CONCAT(YEAR(`pay_date`),"-",MONTH(`pay_date`)) date'))
                        ->select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`pay_date`),MONTH(`pay_date`)'))
                        ->order_by(DB::expr('YEAR(`pay_date`),MONTH(`pay_date`)'),'asc')
                        ->execute();

        $orders = $query->as_array('date');

        $stats_orders_by_month = array();
        foreach ($dates_year as $date) 
        {
            $count_orders = (isset($orders[$date['date']]['count']))?$orders[$date['date']]['count']:0;
            $count_sum = (isset($orders[$date['date']]['total']))?$orders[$date['date']]['total']:0;
            
            $stats_orders_by_month[] = array('date'=>$date['date'],'#orders'=> $count_orders,'$'=>$count_sum);
        } 
        $content->stats_orders_by_month =  $stats_orders_by_month;


        //////////////////////////GROUP BY PRODUCT TOTAL///////////////////
        //visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->select('id_product')
                        ->from('visits')
                        ->where('id_product','is not',NULL)
                        ->group_by('id_product')
                        ->order_by('count','desc')
                        ->execute();
        $content->visits_product = $query->as_array('id_product');

        //orders
        $query = DB::select('id_product')
                        ->select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by('id_product')
                        ->order_by('total','desc')
                        ->execute();
        $content->orders_product = $query->as_array('id_product');

        $products = new Model_Product();
        $content->products = $products->find_all();

        //for the graphic
        $products_total = array();
        foreach ($content->products as $p) 
            $products_total[] = array('name'=>$p->title,'$'=>round($content->orders_product[$p->id_product]['total'],2));
        
        $content->products_total = $products_total;   
        
    }




}