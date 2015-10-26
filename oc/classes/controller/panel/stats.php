<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OC statistics!
 *
 * @package    OC
 * @category   Stats
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Stats extends Auth_Controller {


    public function action_index()
    {

        $this->template->title = __('Stats');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats'))));

        $this->template->styles = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                   '//cdn.jsdelivr.net/sorttable/2/sorttable.min.js',
                                                   'js/Chart.min.js',
                                                   'js/chart.js-php.js',
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
        $dates_year     = Date::range(strtotime('-1 year',$from_date),$to_date,'+1 month','Y-m',array('date'=>0,'count'=> 0),'date');

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
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_today     = (isset($ads[0]['count']))?$ads[0]['count']:0;

         //Current month 
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('MONTH( created )'),'=',date('m'))
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_month     = (isset($ads[0]['count']))?$ads[0]['count']:0;

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


        //Current month 
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->visits_year     = (isset($ads[0]['count']))?$ads[0]['count']:0;


        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits');
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;



        //visits by month 1 year from to_date
        $query = DB::select(DB::expr('DATE_FORMAT(`created`, "%Y-%m") date'))
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
                        ->where(DB::expr('DATE( pay_date )'),'=',date('Y-m-d'))
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


        //current month
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where(DB::expr('MONTH( pay_date )'),'=',date('m'))
                        ->where(DB::expr('YEAR( pay_date )'),'=',date('Y'))
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`pay_date`),MONTH(`pay_date`)'))
                        ->order_by(DB::expr('YEAR(`pay_date`),MONTH(`pay_date`)'),'asc')
                        ->order_by('pay_date','asc')
                        ->execute();


        $orders = $query->as_array();
        $content->orders_month = (isset($orders[0]['count']))?$orders[0]['count']:0;
        $content->amount_month = (isset($orders[0]['total']))?$orders[0]['total']:0;


        //current year
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where(DB::expr('YEAR( pay_date )'),'=',date('Y-m-d'))
                        ->where('status','=',Model_Order::STATUS_PAID);
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`pay_date`)'))
                        ->order_by(DB::expr('YEAR(`pay_date`)'),'asc')
                        ->order_by('pay_date','asc')
                        ->execute();


        $orders = $query->as_array();
        $content->orders_year = (isset($orders[0]['count']))?$orders[0]['count']:0;
        $content->amount_year= (isset($orders[0]['total']))?$orders[0]['total']:0;

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
        $query = DB::select(DB::expr('DATE_FORMAT(`pay_date`, "%Y-%m") date'))
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
                        ->where('created','between',array($my_from_date,$my_to_date))
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
                        ->where('pay_date','between',array($my_from_date,$my_to_date))
                        ->group_by('id_product')
                        ->order_by('total','desc')
                        ->execute();
        $content->orders_product = $query->as_array('id_product');

        //downloads
        $query = DB::select('id_product')
                        ->select(DB::expr('COUNT(id_product) count'))                      
                        ->from(array('orders','o'))
                        ->join(array('downloads','d'))
                        ->using('id_order')
                        ->where('d.created','between',array($my_from_date,$my_to_date))
                        ->group_by('id_product')
                        ->order_by('count','desc')
                        ->execute();
        $content->downloads_product = $query->as_array('id_product');

        //licenses
        $query = DB::select('id_product')
                        ->select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where('status','=',Model_License::STATUS_ACTIVE)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by('id_product')
                        ->order_by('count','desc')
                        ->execute();
        $content->licenses_product = $query->as_array('id_product');

        //tickets closed
        $query = DB::select('id_product')
                        ->select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('status','=',Model_Ticket::STATUS_CLOSED)
                        ->where('id_ticket_parent','=',NULL)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by('id_product')
                        ->order_by('count','desc')
                        ->execute();

        $content->tickets_closed_product = $query->as_array('id_product');

        //tickets open
        $query = DB::select('id_product')
                        ->select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('id_ticket_parent','=',NULL)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by('id_product')
                        ->order_by('count','desc')
                        ->execute();

        $content->tickets_open_product = $query->as_array('id_product');

        $products = new Model_Product();
        $content->products = $products->find_all();

        //for the graphic
        $products_total = array();
        foreach ($content->products as $p) 
            $products_total[] = array('label'=>$p->title,'value'=>(isset($content->orders_product[$p->id_product]))?round($content->orders_product[$p->id_product]['total'],2):0);
        
        $content->products_total = $products_total;

        //////////////////////////DOWNLOADS STATS///////////////////

        //downloads created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads')
                        ->where('created','between',array($my_from_date,$my_to_date));
        
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $downloads = $query->as_array('date');

        $stats_downloads = array();
        foreach ($dates as $date) 
        {
            $count_downloads = (isset($downloads[$date['date']]['count']))?$downloads[$date['date']]['count']:0;
            
            $stats_downloads[] = array('date'=>$date['date'],'#downloads'=> $count_downloads);
        } 
        $content->stats_downloads =  $stats_downloads;


        //Today 
        $query = DB::select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d'));
        
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->downloads_today = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')));
        
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->downloads_yesterday     = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //current month
        $query = DB::select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads')
                        ->where(DB::expr('MONTH( created )'),'=',date('m'))
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        
        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();


        $downloads = $query->as_array();
        $content->downloads_month = (isset($downloads[0]['count']))?$downloads[0]['count']:0;


        //current year
        $query = DB::select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads')
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        
        $query = $query->group_by(DB::expr('YEAR(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();


        $downloads = $query->as_array();
        $content->downloads_year = (isset($downloads[0]['count']))?$downloads[0]['count']:0;


        //total downloads
        $query = DB::select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads');
        
        $query = $query
                        ->execute();

        $downloads = $query->as_array();
        $content->downloads_total = (isset($downloads[0]['count']))?$downloads[0]['count']:0;

        //downloads per month
        $query = DB::select(DB::expr('DATE_FORMAT(`created`, "%Y-%m") date'))
                        ->select(DB::expr('COUNT(id_download) count'))
                        ->from('downloads');
        
        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->execute();

        $downloads = $query->as_array('date');

        $stats_downloads_by_month = array();
        foreach ($dates_year as $date) 
        {
            $count_downloads = (isset($downloads[$date['date']]['count']))?$downloads[$date['date']]['count']:0;
            
            $stats_downloads_by_month[] = array('date'=>$date['date'],'#downloads'=> $count_downloads);
        } 
        $content->stats_downloads_by_month =  $stats_downloads_by_month;

        //////////////////////////LICENSES STATS///////////////////

        //licenses created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where('created','between',array($my_from_date,$my_to_date));
        if ($content->product!==NULL)
            $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $licenses = $query->as_array('date');

        $stats_licenses = array();
        foreach ($dates as $date) 
        {

            $count_licenses = (isset($licenses[$date['date']]['count']))?$licenses[$date['date']]['count']:0;

            $stats_licenses[] = array('date'=>$date['date'],'#licenses'=> $count_licenses);
        } 
        $content->stats_licenses =  $stats_licenses;


        //Today 
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->licenses_today = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->licenses_yesterday     = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //current month
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where(DB::expr('MONTH( created )'),'=',date('m'))
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();


        $licenses = $query->as_array();
        $content->licenses_month = (isset($licenses[0]['count']))?$licenses[0]['count']:0;

        //current year
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses')
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query->group_by(DB::expr('YEAR(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();


        $licenses = $query->as_array();
        $content->licenses_year = (isset($licenses[0]['count']))?$licenses[0]['count']:0;

        //total licenses
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->from('licenses');
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->execute();

        $licenses = $query->as_array();
        $content->licenses_total = (isset($licenses[0]['count']))?$licenses[0]['count']:0;

        //active licenses
        $query = DB::select(DB::expr('COUNT(id_license) count'))
                        ->where(DB::expr('DATE(active_date)'),'<=', DB::expr('DATE(valid_date)'))
                        ->from('licenses');
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
        $query = $query
                        ->execute();

        $licenses = $query->as_array();
        $content->licenses_active = (isset($licenses[0]['count']))?$licenses[0]['count']:0;

        //licenses per month
        $query = DB::select(DB::expr('DATE_FORMAT(`created`, "%Y-%m") date'))
                        ->select(DB::expr('COUNT(id_license) count'))
                        ->select(DB::expr('COUNT(active_date) activated '))
                        ->from('licenses');
        if ($content->product!==NULL)
                $query = $query->where('id_product','=',$content->product->id_product);
                $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->execute();

        $licenses = $query->as_array('date');

        $stats_licenses_by_month = array();
        foreach ($dates_year as $date) 
        {
            $count_licenses = (isset($licenses[$date['date']]['count']))?$licenses[$date['date']]['count']:0;
            
            $activated_licenses = (isset($licenses[$date['date']]['activated']))?$licenses[$date['date']]['activated']:0;
            $stats_licenses_by_month[] = array('date'=>$date['date'],'#licenses'=> $count_licenses,'#activated'=>$activated_licenses);
        } 
        $content->stats_licenses_by_month =  $stats_licenses_by_month;

        //////////////////////////TICKETS STATS///////////////////

        //Today 
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d'));
        
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->tickets_today = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')));
        
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->tickets_yesterday     = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //current month
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where(DB::expr('MONTH( created )'),'=',date('m'))
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        
        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();


        $tickets = $query->as_array();
        $content->tickets_month = (isset($tickets[0]['count']))?$tickets[0]['count']:0;


        //current year
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where(DB::expr('YEAR( created )'),'=',date('Y'));
        
        $query = $query->group_by(DB::expr('YEAR(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`)'),'asc')
                        ->order_by('created','asc')
                        ->execute();

        $tickets = $query->as_array();
        $content->tickets_year = (isset($tickets[0]['count']))?$tickets[0]['count']:0;

        //read tickets
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('status','=', Model_Ticket::STATUS_READ);
        
        $query = $query->execute();

        $tickets = $query->as_array();
        $content->tickets_read = (isset($tickets[0]['count']))?$tickets[0]['count']:0;

        //hold tickets
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('status','=', Model_Ticket::STATUS_HOLD);
        
        $query = $query->execute();

        $tickets = $query->as_array();
        $content->tickets_hold = (isset($tickets[0]['count']))?$tickets[0]['count']:0;

        //closed tickets
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('status','=', Model_Ticket::STATUS_CLOSED);
        
        $query = $query->execute();

        $tickets = $query->as_array();
        $content->tickets_closed = (isset($tickets[0]['count']))?$tickets[0]['count']:0;

        //total tickets
        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets');
        
        $query = $query->execute();

        $tickets = $query->as_array();
        $content->tickets_total = (isset($tickets[0]['count']))?$tickets[0]['count']:0;

        //tickets created last XX days
        //open/created
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('id_ticket_parent','=',NULL)
                        ->where('created','between',array($my_from_date,$my_to_date));
        $query = $query
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $query_closed = DB::select(DB::expr('DATE(read_date) date'))
                        ->select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('read_date', '!=', 'NULL')
                        ->where('status', '=', Model_Ticket::STATUS_CLOSED)
                        ->where('read_date','between',array($my_from_date,$my_to_date));
        $query_closed = $query_closed
                        ->group_by(DB::expr('DATE( read_date )'))
                        ->order_by('date','asc')
                        ->execute();

        $query_answers = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets')
                        ->where('id_ticket_parent','!=',NULL)
                        ->where('created','between',array($my_from_date,$my_to_date));
        $query_answers = $query_answers
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $answers = $query_answers->as_array('date');
        $closed = $query_closed->as_array('date');
        $tickets = $query->as_array('date');

        $stats_tickets = array();
        foreach ($dates as $date) 
        {
            $count_open = (isset($tickets[$date['date']]['count']))?$tickets[$date['date']]['count']:0;
            $count_closed = (isset($closed[$date['date']]['count']))?$closed[$date['date']]['count']:0;
            $count_answers = (isset($answers[$date['date']]['count']))?$answers[$date['date']]['count']:0;
            $stats_tickets[] = array('date'=>$date['date'],'#open'=> $count_open, '#closed' => $count_closed, '#answers'=>$count_answers);
        } 
        $content->stats_tickets =  $stats_tickets;

        //tickets per month
        $query = DB::select(DB::expr('DATE_FORMAT(`created`, "%Y-%m") date'))
                        ->select(DB::expr('COUNT(id_ticket) count'))
                        ->from('tickets');
        
        $query = $query->group_by(DB::expr('YEAR(`created`),MONTH(`created`)'))
                        ->order_by(DB::expr('YEAR(`created`),MONTH(`created`)'),'asc')
                        ->execute();

        $tickets = $query->as_array('date');

        $stats_tickets_by_month = array();
        foreach ($dates_year as $date) 
        {
            $count_tickets = (isset($tickets[$date['date']]['count']))?$tickets[$date['date']]['count']:0;
            
            $stats_tickets_by_month[] = array('date'=>$date['date'],'#tickets'=> $count_tickets);
        } 
        $content->stats_tickets_by_month =  $stats_tickets_by_month;      
        
    }

}