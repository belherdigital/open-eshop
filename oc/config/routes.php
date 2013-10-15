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
 * Item / post new
 * URL::title(__('publish new'))
 */
Route::set('post_new', URL::title(__('publish new')).'.html')
->defaults(array(
		'controller' => 'new',    
		'action'     => 'index',
));

/**
 * search
 */
Route::set('search',URL::title(__('search')).'.html')
->defaults(array(
        'controller' => 'ad',    
        'action'     => 'advanced_search',
));

/**
 * Captcha / contact
 */
Route::set('contact', URL::title(__('contact')).'.html')
->defaults(array(
		'controller' => 'contact',
		'action'	 => 'index',));

/**
 * maps
 */
Route::set('map', URL::title(__('map')).'.html')
->defaults(array(
        'controller' => 'map',
        'action'     => 'index',));
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
Route::set('page','p/<seotitle>.html')
->defaults(array(
        'controller' => 'page',    
        'action'     => 'view',
        'seotitle'	 => '',
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
 * Item / ad view (public)
 */
Route::set('ad', '<category>/<seotitle>.html')
->defaults(array(
		'controller' => 'ad',    
		'action'     => 'view',
));

/**
 * Sort by Category / Location
 */
Route::set('list', '<category>(/<location>)')
->defaults(array(
		'category'	 => 'all',
		'controller' => 'ad',    
		'action'     => 'listing',
));

/*
	user profile route 
 */
 
Route::set('profile', 'user/<seoname>/<action>')
->defaults(array(
		'controller' => 'user',
		'action'     => 'index',
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

// changes the landing page of website, buy checking config.
// it reads config that is stored as a JSON
// if config returns null (doesn't exists), set default controller and action.

$landing = core::config('general.landing_page');
if($landing != NULL)
{
	$landing = json_decode($landing);
	$controller_home = $landing->controller;
	$action_home = $landing->action;
}
else
{
	$controller_home = 'home';
	$action_home = 'index';
}

Route::set('default', '(<controller>(/<action>(/<id>)))')
->defaults(array(
		'controller' =>  $controller_home,
		'action'     => $action_home,
));
