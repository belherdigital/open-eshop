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

    public function before($template = NULL)
    {   
        parent::before();

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats'))->set_url(Route::url('oc-panel',array('controller'  => 'stats')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                   'js/chart.min.js',
                                                   'js/chart.js-php.js',
                                                   'js/oc-panel/stats/dashboard.js');
    }

    public function action_index()
    {
        $this->template->title = __('Stats');

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/dashboard');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        // We assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates
        $now        = time();
        $today      = strtotime('today');
        $yesterday  = strtotime('yesterday');
        $this_month = strtotime(date('01-m-Y'));
        $last_month = strtotime(date('01-m-Y').' -1 month');
        $this_year  = strtotime(date('01-01-Y'));
        $last_year  = strtotime(date('01-01-Y').' -1 year');

        // Dates displayed
        $content->from_date              = date('Y-m-d', $from_date);
        $content->to_date                = date('Y-m-d', $to_date);
        $content->days_ago               = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        // Gross Revenue
        $content->gross_revenue                 = $this->gross_revenue_by_date($from_date, $to_date);
        $content->gross_revenue_total           = $this->gross_revenue_total($from_date, $to_date);
        $content->gross_revenue_total_past      = $this->gross_revenue_total($from_date, $to_date, TRUE);

        $content->gross_revenue_today           = $this->gross_revenue_total($today, $now);
        $content->gross_revenue_today_past      = $this->gross_revenue_total($yesterday, $today);
        $content->gross_revenue_yesterday       = $this->gross_revenue_total($yesterday, $today);
        $content->gross_revenue_yesterday_past  = $this->gross_revenue_total($yesterday, $today, TRUE);
        $content->gross_revenue_month           = $this->gross_revenue_total($this_month, $now);
        $content->gross_revenue_month_past      = $this->gross_revenue_total($last_month, $this_month);
        $content->gross_revenue_year            = $this->gross_revenue_total($this_year, $now);
        $content->gross_revenue_year_past       = $this->gross_revenue_total($last_year, $this_year);
        $content->gross_revenue_total           = $this->gross_revenue_total(0, time());

        // Net Revenue
        $content->net_revenue            = $this->net_revenue_by_date($from_date, $to_date);
        $content->net_revenue_total      = $this->net_revenue_total($from_date, $to_date);
        $content->net_revenue_total_past = $this->net_revenue_total($from_date, $to_date, TRUE);

        // Fees
        $content->fees            = $this->fees_by_date($from_date, $to_date);
        $content->fees_total      = $this->fees_total($from_date, $to_date);
        $content->fees_total_past = $this->fees_total($from_date, $to_date, TRUE);

        // Paid Orders
        $content->paid_orders            = $this->paid_orders_by_date($from_date, $to_date);
        $content->paid_orders_total      = $this->paid_orders_total($from_date, $to_date);
        $content->paid_orders_total_past = $this->paid_orders_total($from_date, $to_date, TRUE);

        // Unpaid Orders
        $content->unpaid_orders            = $this->unpaid_orders_by_date($from_date, $to_date);
        $content->unpaid_orders_total      = $this->unpaid_orders_total($from_date, $to_date);
        $content->unpaid_orders_total_past = $this->unpaid_orders_total($from_date, $to_date, TRUE);

        // Visits
        $content->visits            = $this->visits_by_date($from_date, $to_date);
        $content->visits_total      = $this->visits_total($from_date, $to_date);
        $content->visits_total_past = $this->visits_total($from_date, $to_date, TRUE);

        $content->visits_today           = $this->visits_total($today, $now);
        $content->visits_today_past      = $this->visits_total($yesterday, $today);
        $content->visits_yesterday       = $this->visits_total($yesterday, $today);
        $content->visits_yesterday_past  = $this->visits_total($yesterday, $today, TRUE);
        $content->visits_month           = $this->visits_total($this_month, $now);
        $content->visits_month_past      = $this->visits_total($last_month, $this_month);
        $content->visits_year            = $this->visits_total($this_year, $now);
        $content->visits_year_past       = $this->visits_total($last_year, $this_year);
        $content->visits_total           = $this->visits_total(0, time());

        // Downloads
        $content->downloads            = $this->downloads_by_date($from_date, $to_date);
        $content->downloads_total      = $this->downloads_total($from_date, $to_date);
        $content->downloads_total_past = $this->downloads_total($from_date, $to_date, TRUE);

        $content->downloads_today           = $this->downloads_total($today, $now);
        $content->downloads_today_past      = $this->downloads_total($yesterday, $today);
        $content->downloads_yesterday       = $this->downloads_total($yesterday, $today);
        $content->downloads_yesterday_past  = $this->downloads_total($yesterday, $today, TRUE);
        $content->downloads_month           = $this->downloads_total($this_month, $now);
        $content->downloads_month_past      = $this->downloads_total($last_month, $this_month);
        $content->downloads_year            = $this->downloads_total($this_year, $now);
        $content->downloads_year_past       = $this->downloads_total($last_year, $this_year);
        $content->downloads_total           = $this->downloads_total(0, time());

        // Licenses
        $content->licenses            = $this->licenses_by_date($from_date, $to_date);
        $content->licenses_total      = $this->licenses_total($from_date, $to_date);
        $content->licenses_total_past = $this->licenses_total($from_date, $to_date, TRUE);

        $content->licenses_today           = $this->licenses_total($today, $now);
        $content->licenses_today_past      = $this->licenses_total($yesterday, $today);
        $content->licenses_yesterday       = $this->licenses_total($yesterday, $today);
        $content->licenses_yesterday_past  = $this->licenses_total($yesterday, $today, TRUE);
        $content->licenses_month           = $this->licenses_total($this_month, $now);
        $content->licenses_month_past      = $this->licenses_total($last_month, $this_month);
        $content->licenses_year            = $this->licenses_total($this_year, $now);
        $content->licenses_year_past       = $this->licenses_total($last_year, $this_year);
        $content->licenses_total           = $this->licenses_total(0, time());

        // Tickets Opened
        $content->tickets_opened            = $this->tickets_opened_by_date($from_date, $to_date);
        $content->tickets_opened_total      = $this->tickets_opened_total($from_date, $to_date);
        $content->tickets_opened_total_past = $this->tickets_opened_total($from_date, $to_date, TRUE);

        $content->tickets_opened_today           = $this->tickets_opened_total($today, $now);
        $content->tickets_opened_today_past      = $this->tickets_opened_total($yesterday, $today);
        $content->tickets_opened_yesterday       = $this->tickets_opened_total($yesterday, $today);
        $content->tickets_opened_yesterday_past  = $this->tickets_opened_total($yesterday, $today, TRUE);
        $content->tickets_opened_month           = $this->tickets_opened_total($this_month, $now);
        $content->tickets_opened_month_past      = $this->tickets_opened_total($last_month, $this_month);
        $content->tickets_opened_year            = $this->tickets_opened_total($this_year, $now);
        $content->tickets_opened_year_past       = $this->tickets_opened_total($last_year, $this_year);
        $content->tickets_opened_total           = $this->tickets_opened_total(0, time());

        // Tickets Answered
        $content->tickets_answered            = $this->tickets_answered_by_date($from_date, $to_date);
        $content->tickets_answered_total      = $this->tickets_answered_total($from_date, $to_date);
        $content->tickets_answered_total_past = $this->tickets_answered_total($from_date, $to_date, TRUE);

        // Tickets Closed
        $content->tickets_closed            = $this->tickets_closed_by_date($from_date, $to_date);
        $content->tickets_closed_total      = $this->tickets_closed_total($from_date, $to_date);
        $content->tickets_closed_total_past = $this->tickets_closed_total($from_date, $to_date, TRUE);

        $content->chart_config           = array('height'  => 94,
                                                 'width'   => 378,
                                                 'options' => array('responsive' => true,
                                                                    'scales' => array('xAxes' => array(array('display' => false)),
                                                                                      'yAxes' => array(array('display' => false,
                                                                                                             'ticks'   => array('min' => 0)))),
                                                                    'legend' => array('display' => false)));
        $content->chart_colors           = array(array('fill'        => 'rgba(33,150,243,.1)',
                                                       'stroke'      => 'rgba(33,150,243,.8)',
                                                       'point'       => 'rgba(33,150,243,.8)',
                                                       'pointStroke' => 'rgba(33,150,243,.8)'));

    }

    /**
     * Gross Revenue Stats
     * 
     */
    public function action_gross_revenue()
    {
        $this->template->title = __('Gross Revenue');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'gross_revenue')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->gross_revenue_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->gross_revenue_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->gross_revenue_total($from_date, $to_date);
        $content->past_total                   = $this->gross_revenue_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->gross_revenue_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->gross_revenue_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->gross_revenue_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->gross_revenue_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->gross_revenue_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->gross_revenue_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->gross_revenue_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->gross_revenue_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->gross_revenue_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'MONEY';
    }

    /**
     * Net Revenue Stats
     * 
     */
    public function action_net_revenue()
    {
        $this->template->title = __('Net Revenue');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'net_revenue')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->net_revenue_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->net_revenue_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->net_revenue_total($from_date, $to_date);
        $content->past_total                   = $this->net_revenue_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->net_revenue_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->net_revenue_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->net_revenue_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->net_revenue_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->net_revenue_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->net_revenue_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->net_revenue_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->net_revenue_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->net_revenue_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'MONEY';
    }

    /**
     * Fees Stats
     * 
     */
    public function action_fees()
    {
        $this->template->title = __('Fees');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'fees')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->fees_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->fees_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->fees_total($from_date, $to_date);
        $content->past_total                   = $this->fees_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->fees_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->fees_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->fees_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->fees_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->fees_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->fees_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->fees_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->fees_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->fees_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'MONEY';
    }

    /**
     * Paid Orders Stats
     * 
     */
    public function action_paid_orders()
    {
        $this->template->title = __('Paid Orders');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'paid_orders')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->paid_orders_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->paid_orders_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->paid_orders_total($from_date, $to_date);
        $content->past_total                   = $this->paid_orders_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->paid_orders_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->paid_orders_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->paid_orders_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->paid_orders_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->paid_orders_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->paid_orders_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->paid_orders_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->paid_orders_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->paid_orders_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'INTEGER';
    }

    /**
     * Unpaid Orders Stats
     * 
     */
    public function action_unpaid_orders()
    {
        $this->template->title = __('Unpaid Orders');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'unpaid_orders')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->unpaid_orders_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->unpaid_orders_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->unpaid_orders_total($from_date, $to_date);
        $content->past_total                   = $this->unpaid_orders_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->unpaid_orders_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->unpaid_orders_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->unpaid_orders_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->unpaid_orders_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->unpaid_orders_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->unpaid_orders_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->unpaid_orders_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->unpaid_orders_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->unpaid_orders_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'INTEGER';
    }

    /**
     * Visits
     * 
     */
    public function action_visits()
    {
        $this->template->title = __('Visits');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'visits')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->visits_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->visits_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->visits_total($from_date, $to_date);
        $content->past_total                   = $this->visits_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->visits_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->visits_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->visits_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->visits_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->visits_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->visits_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->visits_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->visits_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->visits_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'INTEGER';
    }

    /**
     * Visits
     * 
     */
    public function action_licenses()
    {
        $this->template->title = __('Licenses');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'licenses')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        if (Core::get('compare_products') == 1)
        {
            foreach ($products as $product)
            {
                $products_data[] = $this->licenses_by_date($from_date, $to_date, $product);
            }

            $content->current_by_date = $this->merge_products_array($products_data);
        }
        else {
            $content->current_by_date = $this->licenses_by_date($from_date, $to_date);
        }

        $content->current_total                = $this->licenses_total($from_date, $to_date);
        $content->past_total                   = $this->licenses_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->licenses_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->licenses_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->licenses_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->licenses_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->licenses_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->licenses_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->licenses_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->licenses_total(strtotime('-12 months'), time(), TRUE);

        foreach ($products as $product)
        {
            $products_data[$product->id_product]['total'] = $this->licenses_total($from_date, $to_date, FALSE, $product->id_product);
            $products_data[$product->id_product]['customers'] = $this->paid_orders_by_product($product->id_product, $from_date, $to_date);
        }

        $content->products      = $products;
        $content->products_data = $products_data;

        $content->num_format = 'INTEGER';
    }

    /**
     * Downloads
     * 
     */
    public function action_downloads()
    {
        $this->template->title = __('Downloads');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'downloads')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date = $this->downloads_by_date($from_date, $to_date);

        $content->current_total                = $this->downloads_total($from_date, $to_date);
        $content->past_total                   = $this->downloads_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->downloads_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->downloads_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->downloads_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->downloads_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->downloads_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->downloads_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->downloads_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->downloads_total(strtotime('-12 months'), time(), TRUE);

        $content->num_format = 'INTEGER';

    }

    /**
     * Tickets Opened
     * 
     */
    public function action_tickets_opened()
    {
        $this->template->title = __('Tickets Opened');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'tickets_opened')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date = $this->tickets_opened_by_date($from_date, $to_date);

        $content->current_total                = $this->tickets_opened_total($from_date, $to_date);
        $content->past_total                   = $this->tickets_opened_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->tickets_opened_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->tickets_opened_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->tickets_opened_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->tickets_opened_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->tickets_opened_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->tickets_opened_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->tickets_opened_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->tickets_opened_total(strtotime('-12 months'), time(), TRUE);

        $content->num_format = 'INTEGER';

    }

    /**
     * Tickets Answered
     * 
     */
    public function action_tickets_answered()
    {
        $this->template->title = __('Tickets Answered');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'tickets_answered')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date = $this->tickets_answered_by_date($from_date, $to_date);

        $content->current_total                = $this->tickets_answered_total($from_date, $to_date);
        $content->past_total                   = $this->tickets_answered_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->tickets_answered_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->tickets_answered_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->tickets_answered_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->tickets_answered_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->tickets_answered_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->tickets_answered_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->tickets_answered_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->tickets_answered_total(strtotime('-12 months'), time(), TRUE);

        $content->num_format = 'INTEGER';

    }

    /**
     * Tickets Closed
     * 
     */
    public function action_tickets_closed()
    {
        $this->template->title = __('Tickets Closed');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'tickets_closed')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        //all  products
        $products = new Model_Product();
        $products = $products->cached()->find_all();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date = $this->tickets_closed_by_date($from_date, $to_date);

        $content->current_total                = $this->tickets_closed_total($from_date, $to_date);
        $content->past_total                   = $this->tickets_closed_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->tickets_closed_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->tickets_closed_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->tickets_closed_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->tickets_closed_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->tickets_closed_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->tickets_closed_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->tickets_closed_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->tickets_closed_total(strtotime('-12 months'), time(), TRUE);

        $content->num_format = 'INTEGER';

    }

    /**
     * Gross Revenue value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function gross_revenue_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('SUM(amount) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Gross Revenue by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function gross_revenue_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(pay_date) date'))
            ->select(DB::expr('SUM(amount) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(pay_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '$' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Net Revenue value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function net_revenue_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('SUM(amount_net) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Net Revenue by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function net_revenue_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(pay_date) date'))
            ->select(DB::expr('SUM(amount_net) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(pay_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '$' => $count_sum);
        
            $label_counter++;
        }

        return $ret;

    }

    /**
     * Fees value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function fees_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('SUM(gateway_fee) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Fees by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function fees_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(pay_date) date'))
            ->select(DB::expr('SUM(gateway_fee) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(pay_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '$' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Paid Orders value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function paid_orders_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_order) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Paid Orders by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function paid_orders_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(pay_date) date'))
            ->select(DB::expr('COUNT(id_order) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_PAID);

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(pay_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Unpaid Orders value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function unpaid_orders_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_order) total'))
            ->from('orders')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_CREATED);

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Unpaid Orders by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function unpaid_orders_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_order) total'))
            ->from('orders')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('status', '=', Model_Order::STATUS_CREATED);

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Visits value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function visits_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_visit) total'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Visits by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function visits_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_visit) total'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Licenses value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @param  integer    $id_product
     * @return integer
     */
    private function licenses_total($from_date, $to_date, $past_period = FALSE, $id_product = NULL)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_license) total'))
            ->from('licenses')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        if ($id_product !== NULL)
            $query = $query->where('id_product', '=', $id_product);

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Licenses by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @param  object    $product
     * @return array
     */
    private function licenses_by_date($from_date, $to_date, $product = NULL)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_license) total'))
            ->from('licenses')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Downloads value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function downloads_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_download) total'))
            ->from('downloads')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Downloads by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function downloads_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_download) total'))
            ->from('downloads')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Tickets Opened value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function tickets_opened_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_ticket) total'))
            ->from('tickets')
            ->where('id_ticket_parent','=',NULL)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Tickets Opened by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function tickets_opened_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_ticket) total'))
            ->from('tickets')
            ->where('id_ticket_parent','=',NULL)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Tickets Answered value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function tickets_answered_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_ticket) total'))
            ->from('tickets')
            ->where('id_ticket_parent','!=',NULL)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Tickets Answered by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function tickets_answered_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_ticket) total'))
            ->from('tickets')
            ->where('id_ticket_parent','!=',NULL)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Tickets Closed value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function tickets_closed_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_ticket) total'))
            ->from('tickets')
            ->where('read_date', '!=', 'NULL')
            ->where('status', '=', Model_Ticket::STATUS_CLOSED)
            ->where('read_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Tickets Closed by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function tickets_closed_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(read_date) date'))
            ->select(DB::expr('COUNT(id_ticket) total'))
            ->from('tickets')
            ->where('read_date', '!=', 'NULL')
            ->where('status', '=', Model_Ticket::STATUS_CLOSED)
            ->where('read_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(read_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = count($dates) > 30 ? Num::round(count($dates)/30) : 1;

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Total paid orders filtered by product
     * @param  integer    $id_product
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function paid_orders_by_product($id_product, $from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('count(id_product) total'))
            ->from('orders')
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)))
            ->where('id_product', '=', $id_product)
            ->where('status', '=', Model_Order::STATUS_PAID);
        
        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Merge array of products formatted to generate charts
     * @param  array $products_data
     * @return array
     */
    private function merge_products_array($products_data)
    {
        $total_products_data = count($products_data);

        foreach ($products_data[0] as $i => $product_data)
        {
            for ($x = 1; $x < $total_products_data; $x++)
            {
                $products_data[0][$i] = array_merge($products_data[0][$i], $products_data[$x][$i]);
            }
        }

        return $products_data[0];
    }

    /**
     * Dates range that we will be filtering
     * @param  integer $from_date
     * @param  integer $to_date
     * @return array
     */
    private function dates_range($from_date, $to_date)
    {
        return Date::range($from_date, $to_date, '+1 day', 'Y-m-d', array('date' => 0, 'total' => 0), 'date');
    }

}