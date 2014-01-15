<?php defined('SYSPATH') or die('No direct access allowed.');

// -- Routes Configuration and initialization -----------------------------------------

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

/**
 * Reserved pages for OC usage. They use the i18n translation
 * We will use them with extension .htm to avoid repetitions with others.
 */



/**
 * search
 */
Route::set('search',URL::title(__('search')).'.html')
->defaults(array(
        'controller' => 'product',    
        'action'     => 'search',
));

/**
 * Captcha / contact
 */
Route::set('contact', URL::title(__('contact')).'.html')
->defaults(array(
		'controller' => 'contact',
		'action'	 => 'index',));


/**
 * maintenance
 */
Route::set('maintenance', URL::title(__('maintenance')).'.html')
->defaults(array(
        'controller' => 'maintenance',
        'action'     => 'index',));

/**
 * page view public
 */
Route::set('page','<seotitle>.html')
->defaults(array(
        'controller' => 'page',    
        'action'     => 'view',
        'seotitle'   => '',
));


/**
 * rss for blog
 */
Route::set('rss-blog','rss/blog.xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'blog',
));

/**
 * rss
 */
Route::set('rss','rss(/<category>(/<location>)).xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'index',
));

/**
 * site info json
 */
Route::set('sitejson','info.json')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'info',
));



//-------END reserved pagesd

/**
 * user admin/panel route
 */
Route::set('oc-panel', 'oc-panel(/<controller>(/<action>(/<id>(/<current_url>))))')
->defaults(array(
        'directory'  => 'panel',
        'controller' => 'home',
        'action'     => 'index',
));


/**
 * blog
 */
Route::set('blog', 'blog(/<seotitle>.html)')
->defaults(array(
        'controller' => 'blog',    
        'action'     => 'index',
));


/**
 * forum new topic
 */
Route::set('forum-new', 'forum/'.URL::title(__('new topic')).'.html')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'new',
));

/**
 * forum topic
 */
Route::set('forum-topic', 'forum/<forum>/<seotitle>.html')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'topic',
));

/**
 * specific forum list of topics
 */
Route::set('forum-list', 'forum(/<forum>)')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'list',
));

/**
 * all forums / home page
 */
Route::set('forum-home', 'forum')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'index',
));


/**
 * FAQ
 */
Route::set('faq', 'faq(/<seotitle>.html)')
->defaults(array(
        'controller' => 'faq',    
        'action'     => 'index',
));


/**
 * *************ITEMS ROUTES
 */

/**
 * Item / product view minimal (public)
 */
Route::set('product-minimal', '<category>/embed/<seotitle>.html')
->defaults(array(
        'controller' => 'product',    
        'action'     => 'view',
        'ext'        => 1
));

/**
 * Item / product goal page, were we insert the google anayltics goal + content selected in the settings.
 */
Route::set('product-goal', '<category>/thanks/<order>/<seotitle>.html')
->defaults(array(
        'controller' => 'product',    
        'action'     => 'goal',
        'order'      => 'goal',
));

/**
 * Item / product view preview/demo
 */
Route::set('product-demo', '<category>/demo/<seotitle>.html')
->defaults(array(
        'controller' => 'product',    
        'action'     => 'demo',
));

/**
 * Item paypal form
 */
Route::set('product-paypal', '<category>/paypal/<seotitle>.html')
->defaults(array(
        'controller' => 'paypal',    
        'action'     => 'pay',
));

/**
 * Item / product view (public)
 */
Route::set('product', '<category>/<seotitle>.html')
->defaults(array(
        'controller' => 'product',    
        'action'     => 'view',
));



/**
 * Sort by Category
 */
Route::set('list', '<category>')
->defaults(array(
        'category'   => 'all',
        'controller' => 'product',    
        'action'     => 'listing',
));



/**
 * Error router
 */
Route::set('error', 'oc-error/<action>/<origuri>(/<message>)',
array('action' => '[0-9]++',
                    	  'origuri' => '.+', 
                    	  'message' => '.+'))
->defaults(array(
    'controller' => 'error',
    'action'     => 'index'
));

/**
 * Default route
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
->defaults(array(
		'controller' =>  'home',
		'action'     => 'index',
));
