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

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats'))->set_url(Route::url('oc-panel',array('controller'  => 'stats'))));

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

        // Dates displayed
        $content->from_date              = date('Y-m-d', $from_date);
        $content->to_date                = date('Y-m-d', $to_date);
        $content->days_ago               = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        // Gross Revenue
        $content->gross_revenue            = $this->gross_revenue_by_date($from_date, $to_date);
        $content->gross_revenue_total      = $this->gross_revenue_total($from_date, $to_date);
        $content->gross_revenue_total_past = $this->gross_revenue_total($from_date, $to_date, TRUE);

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

        // Downloads
        $content->downloads            = $this->downloads_by_date($from_date, $to_date);
        $content->downloads_total      = $this->downloads_total($from_date, $to_date);
        $content->downloads_total_past = $this->downloads_total($from_date, $to_date, TRUE);

        // Licenses
        $content->licenses            = $this->licenses_by_date($from_date, $to_date);
        $content->licenses_total      = $this->licenses_total($from_date, $to_date);
        $content->licenses_total_past = $this->licenses_total($from_date, $to_date, TRUE);

        // Tickets Opened
        $content->tickets_opened            = $this->tickets_opened_by_date($from_date, $to_date);
        $content->tickets_opened_total      = $this->tickets_opened_total($from_date, $to_date);
        $content->tickets_opened_total_past = $this->tickets_opened_total($from_date, $to_date, TRUE);

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
                                                 'options' => array('animation'           => false,
                                                                    'responsive'          => true,
                                                                    'bezierCurve'         => true,
                                                                    'bezierCurveTension'  => '.25',
                                                                    'showScale'           => false,
                                                                    'pointDotRadius'      => 0,
                                                                    'pointDotStrokeWidth' => 0,
                                                                    'pointDot'            => false,
                                                                    'showTooltips'        => false));
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
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'gross_revenue'))));

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
    }

    /**
     * Net Revenue Stats
     * 
     */
    public function action_net_revenue()
    {
        $this->template->title = __('Net Revenue');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'net_revenue'))));

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
    }

    /**
     * Fees Stats
     * 
     */
    public function action_fees()
    {
        $this->template->title = __('Fees');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'fees'))));

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
    }

    /**
     * Paid Orders Stats
     * 
     */
    public function action_paid_orders()
    {
        $this->template->title = __('Paid Orders');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'paid_orders'))));

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
    }

    /**
     * Unpaid Orders Stats
     * 
     */
    public function action_unpaid_orders()
    {
        $this->template->title = __('Unpaid Orders');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'unpaid_orders'))));

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
    }

    /**
     * Visits
     * 
     */
    public function action_visits()
    {
        $this->template->title = __('Visits');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'visits'))));

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
    }

    /**
     * Visits
     * 
     */
    public function action_licenses()
    {
        $this->template->title = __('Licenses');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'licenses'))));

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
    }

    /**
     * Downloads
     * 
     */
    public function action_downloads()
    {
        $this->template->title = __('Downloads');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'downloads'))));

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

    }

    /**
     * Tickets Opened
     * 
     */
    public function action_tickets_opened()
    {
        $this->template->title = __('Tickets Opened');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'tickets_opened'))));

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

    }

    /**
     * Tickets Answered
     * 
     */
    public function action_tickets_answered()
    {
        $this->template->title = __('Tickets Answered');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'tickets_answered'))));

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

    }

    /**
     * Tickets Closed
     * 
     */
    public function action_tickets_closed()
    {
        $this->template->title = __('Tickets Closed');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'tickets_closed'))));

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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

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

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '$' => $count_sum);
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

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

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '$' => $count_sum);
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

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

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '$' => $count_sum);
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

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

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

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

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);
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

        $query = DB::select(DB::expr('COUNT(id_visit) count'))
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_visit) count'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);
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

        $query = DB::select(DB::expr('COUNT(id_license) count'))
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_license) count'))
            ->from('licenses')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        if ($product !== NULL)
            $query = $query->where('id_product', '=', $product->id_product);

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], ($product !== NULL ? '#' . $product->id_product . ' ' . $product->title . ' ' : NULL) . '#' => $count_sum);
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

        $query = DB::select(DB::expr('COUNT(id_download) count'))
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_download) count'))
            ->from('downloads')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], '#' => $count_sum);
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

        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_ticket) count'))
            ->from('tickets')
            ->where('id_ticket_parent','=',NULL)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], '#' => $count_sum);
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

        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_ticket) count'))
            ->from('tickets')
            ->where('id_ticket_parent','!=',NULL)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], '#' => $count_sum);
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

        $query = DB::select(DB::expr('COUNT(id_ticket) count'))
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
        $dates = Date::range($from_date, $to_date, '+1 day','Y-m-d', array('date' => 0, 'count' => 0), 'date');

        $query = DB::select(DB::expr('DATE(read_date) date'))
            ->select(DB::expr('COUNT(id_ticket) count'))
            ->from('tickets')
            ->where('read_date', '!=', 'NULL')
            ->where('status', '=', Model_Ticket::STATUS_CLOSED)
            ->where('read_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(read_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        foreach ($dates as $k => $date) 
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;
            
            $ret[] = array('date' => $date['date'], '#' => $count_sum);
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

}