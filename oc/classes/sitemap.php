<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sitemap 
 *
 * @package    OC
 * @category   Tools
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Sitemap {

	
	 /**
     * returns last time the sitemap was generated
     * @return int 
     */
    public static function last_generated_time()
    {
        if (file_exists(DOCROOT.'sitemap-index.xml'))
            $time = filemtime(DOCROOT.'sitemap-index.xml');
        elseif(file_exists(DOCROOT.'sitemap.xml'))
            $time = filemtime(DOCROOT.'sitemap.xml');
        else
            $time = strtotime('-1 month');

        return $time;
    }
    /**
     * 
     * generate sitemap
     */
    public static function generate($force = FALSE)
    {
        //start time
        $start_time = microtime(TRUE);

        /**
         * only generate the sitemap if older than XXX
         */
        if ( time()>= (self::last_generated_time()+core::config('sitemap.expires')) OR $force == TRUE)
        {

            $site_url = Core::config('general.base_url');

            // include class
            require Kohana::find_file('vendor/sitemap', 'SitemapGenerator');

            // create object
            $sitemap = new SitemapGenerator($site_url, DOCROOT);

            // will create also compressed (gzipped) sitemap
            $sitemap->createGZipFile = TRUE;

            // determine how many urls should be put into one file
            $sitemap->maxURLsPerSitemap = 10000;

            // sitemap file name
            $sitemap->sitemapFileName = 'sitemap.xml';

            // sitemap index file name
            $sitemap->sitemapIndexFileName = 'sitemap-index.xml';

            // robots file name
            //$sitemap->robotsFileName = 'robots.txt';
            

            //pages CMS 
            $pages =  new Model_Content();
            $pages = $pages->select('seotitle')->where('type','=','page')->where('status','=','1')->find_all();

            foreach($pages as $page)
            {
                $url = Route::url('page',  array('seotitle'=>$page->seotitle));
                $sitemap->addUrl($url, date('c',Date::mysql2unix($page->created)),  'monthly',    '0.5');
            }

            //FAQ CMS 
            if (core::config('general.faq')==1)
            {
                $pages =  new Model_Content();
                $pages = $pages->select('seotitle')->where('type','=','help')->where('status','=','1')->find_all();
                $sitemap->addUrl(Route::url('faq'), date('c'),  'monthly',    '0.5');
                foreach($pages as $page)
                {
                    $url = Route::url('faq',  array('seotitle'=>$page->seotitle));
                    $sitemap->addUrl($url, date('c',Date::mysql2unix($page->created)),  'monthly',    '0.5');
                }
            }

          
            //categories
            $cats =  new Model_Category();
            $cats = $cats->select('seoname')->where('id_category','!=',1)->find_all();
            foreach($cats as $cat)
            {
                $url = Route::url('list',  array('category'=>$cat->seoname));
                $sitemap->addUrl($url, date('c'),  'daily',    '0.7');
            }
            
            //all products
            $products = DB::select('p.seotitle')
                ->select(array('c.seoname','category'),'p.title','p.created')
                ->from(array('products', 'p'))
                ->join(array('categories', 'c'),'INNER')
                ->on('p.id_category','=','c.id_category')
                ->where('p.status','=',Model_Product::STATUS_ACTIVE)
                ->order_by('created','desc')
                ->as_object()
                ->execute();

            foreach($products as $p)
            {
                $url= Route::url('product',  array('category'=>$p->category,'seotitle'=>$p->seotitle));
                $sitemap->addUrl($url, date('c'),  'monthly',    '0.5');
                $url= Route::url('product-review',  array('category'=>$p->category,'seotitle'=>$p->seotitle));
                $sitemap->addUrl($url, date('c'),  'weekly',    '0.6');
            }

            //all the blog posts
            if (core::config('general.blog')==1)
            {
                $sitemap->addUrl(Route::url('blog'), date('c'), 'daily',    '0.7');
                $posts = new Model_Post();
                $posts = $posts->where('status','=', 1)
                        ->where('id_forum','IS',NULL)
                        ->order_by('created','desc')
                        ->find_all();
                foreach ($posts as $post) 
                {
                    $url= Route::url('blog',  array('seotitle'=>$post->seotitle));
                    $sitemap->addUrl($url, date('c'),  'monthly',    '0.5');
                }
            }


            //all the forums and topics
            if (core::config('general.forums')==1)
            {
                $sitemap->addUrl(Route::url('forum-home'), date('c'), 'monthly',    '0.5' );

                $forums =  new Model_Forum();
                $forums = $forums->select('seoname')->find_all();
                foreach($forums as $forum)
                {
                    $url = Route::url('forum-list',  array('forum'=>$forum->seoname));
                    $sitemap->addUrl($url, date('c'),  'daily',    '0.7');
                }

                //all the topics
                $posts = new Model_Post();
                $posts = $posts->where('status','=', Model_Post::STATUS_ACTIVE)
                        ->where('id_forum','IS NOT',NULL)
                        ->where('id_post_parent','IS',NULL)
                        ->order_by('created','desc')
                        ->find_all();
                foreach ($posts as $post) 
                {
                    $url= Route::url('forum-topic',  array('seotitle'=>$post->seotitle,'forum'=>$post->forum->seoname));
                    $sitemap->addUrl($url, date('c'),'daily',    '0.7');
                }
            }
            
            try
            {
                // create sitemap
                $sitemap->createSitemap();
                // write sitemap as file
                $sitemap->writeSitemap();
                // update robots.txt file
                //$sitemap->updateRobots();
                // submit sitemaps to search engines
                $result = $sitemap->submitSitemap();
                // shows each search engine submitting status
                // echo '<pre>'.print_r($result,1).'</pre>';
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            $ret = 'Memory peak '.round(memory_get_peak_usage()/(1024*1024),2).' MB -';

        }//end if new jobs cache
        else//not any new job no need of generating the sitemap
        {
            $ret = __('No sitemap generated');
        }

        return $ret.' Time: '.round( microtime(TRUE) - $start_time,2 ).'s';
    }//end sitemap generation

}