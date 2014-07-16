<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mixed tools for admin
 *
 * @package    OC
 * @category   Tools
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Tools extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Tools'))->set_url(Route::url('oc-panel',array('controller'  => 'tools'))));
        
    }
    
    public function action_index()
    {
        //@todo just a view with links?
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'update','action'=>'index')));  
    }


    public function action_optimize()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Optimize DB')));
        
        $this->template->title = __('Optimize DB');

        $db = Database::instance('default');

        //force optimize all tables
        if (Core::get('force')==1)
        {
            $tables = $db->query(Database::SELECT, 'SHOW TABLES');

            foreach ($tables as $table)
            {
                $table = array_values($table);
                $to[] = $table[0];
            }
            $db->query(Database::SELECT, 'OPTIMIZE TABLE '.implode(', ', $to));
            Alert::set(Alert::SUCCESS,__('Database Optimized'));
        }


        //get tables names and the size and the index
        $total_space = 0;
        $total_gain  = 0;
        $tables_info = array();
        
        $tables = $db->query(Database::SELECT, 'SHOW TABLE STATUS');

        foreach ($tables as $table) 
        {
            $tot_data = $table['Data_length'];
            $tot_idx  = $table['Index_length'];
            $tot_free = $table['Data_free'];

            $tables_info[] = array( 'name'  => $table['Name'],
                                    'rows'  => $table['Rows'],
                                    'space' => round (($tot_data + $tot_idx) / 1024,3),
                                    'gain'  => round ($tot_free / 1024,3),
                                    );

            $total_space += ($tot_data + $tot_idx) / 1024;
            $total_gain += $tot_free / 1024;
        }


        $this->template->content = View::factory('oc-panel/pages/tools/optimize',array('tables'=>$tables_info,
                                                                                        'total_gain'=>$total_gain,
                                                                                        'total_space'=>$total_space,));
    }

    public function action_cache()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Cache')));
        
        $this->template->title = __('Cache');

        $cache_config = Core::config('cache.'.Core::config('cache.default'));

        //force clean cache
        if (Core::get('force')==1)
        {
            Core::delete_cache();
            Alert::set(Alert::SUCCESS,__('All cache deleted'));

        }
        //garbage collector
        elseif (Core::get('force')==2)
        {
            Cache::instance()->garbage_collect();
            Theme::delete_minified();
            Alert::set(Alert::SUCCESS,__('Deleted expired cache'));

        }
        

        $this->template->content = View::factory('oc-panel/pages/tools/cache',array('cache_config'=>$cache_config));
    }


    public function action_phpinfo()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('PHP Info')));
        
        $this->template->title = __('PHP Info');

        //getting the php info clean!
        ob_start();                                                                                                        
        phpinfo();                                                                                                     
        $phpinfo = ob_get_contents();                                                                                         
        ob_end_clean();  
        //strip the body html                                                                                                  
        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
        //adding our class
        $phpinfo = str_replace('<table', '<table class="table table-striped  table-bordered"', $phpinfo);

        $this->template->content = View::factory('oc-panel/pages/tools/phpinfo',array('phpinfo'=>$phpinfo));

    }

    public function action_logs()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('System logs')));
        
        $this->template->title = __('System logs');

        $this->template->styles = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js', 'js/oc-panel/logs.js');
        
        
        $date = core::get('date',date('Y-m-d'));

        $file = APPPATH.'logs/'.str_replace('-', '/', $date).'.php';

        if (file_exists($file))
            $log = file_get_contents($file);
        else $log = NULL;

        $this->template->content = View::factory('oc-panel/pages/tools/logs',array('file'=>$file,'log'=>$log,'date'=>$date));
    }


    public function action_sitemap()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Sitemap')));
        
        $this->template->title = __('Sitemap');

        
        // all sitemap config values
        $sitemapconfig = new Model_Config();
        $config = $sitemapconfig->where('group_name', '=', 'sitemap')->find_all();
      
        // save only changed values
        if($this->request->post())
        {
            foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 
                
                if($config_res != $c->config_value)
                {
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Sitemap Configuration updated'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap')));
        }

        //force regenerate sitemap
        if (Core::get('force')==1)
            Alert::set(Alert::SUCCESS, Sitemap::generate(TRUE));

        $this->template->content = View::factory('oc-panel/pages/tools/sitemap');
    }







}