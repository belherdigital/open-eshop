<?php
/**
 * Debug variable used in Bootstrap, this will setup the Kohana::$environment =  Kohana::DEVELOPMENT;
 * Seted to TRUE:
 *  - disables the cache,  
 *  - enable the profiler in the bottom of the page 
 *  - Enable logs for anything (false=only ERRORs)
 *  - Displays the full error stack instead of friendly page
 */
define('OC_DEBUG', FALSE);

/**
 * Where the application for Open Classifieds is installed.
 */
$application = '/oc';

/**
 * The directory in which your external modules are located.
 *
 */
$modules = $application.'/modules';

/**
 * The directory in which the Kohana resources are located. The system
 * directory must contain the classes/kohana.php file.
 *
 * @see  http://kohanaframework.org/guide/about.install#system
 */
$system = $application.'/kohana/system';

/**
 * The directory in which KO modules are located.
 *
 * @see  http://kohanaframework.org/guide/about.install#modules
 */
$komodules = $application.'/kohana/modules';

/**
 * The directory where common Open Classifieds files are 
 * @see https://github.com/open-classifieds/common
 * @see https://github.com/open-classifieds/openclassifieds2/blob/master/CONTRIBUTING.md
 */
$common = $application.'/common';

/**
 * The default extension of resource files. If you change this, all resources
 * must be renamed to use the new extension.
 *
 * @see  http://kohanaframework.org/guide/about.install#ext
 */
define('EXT', '.php');

/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 * @see  http://php.net/error_reporting
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 *
 * When using a legacy application with PHP >= 5.3, it is recommended to disable
 * deprecated notices. Disable with: E_ALL & ~E_DEPRECATED
 */
ini_set('display_errors', 'On');
 
# Error reporting may look like this but E_ALL is only what we need
error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * End of standard configuration! Changing any of the code below should only be
 * attempted by those with a working knowledge of Kohana internals.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 */

// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// Make the application relative to the docroot, for symlink'd index.php
if ( ! is_dir($application) AND is_dir(DOCROOT.$application))
    $application = DOCROOT.$application;

// Make the modules relative to the docroot, for symlink'd index.php
if ( ! is_dir($modules) AND is_dir(DOCROOT.$modules))
    $modules = DOCROOT.$modules;

// Make the modules relative to the docroot, for symlink'd index.php
if ( ! is_dir($komodules) AND is_dir(DOCROOT.$komodules))
    $komodules = DOCROOT.$komodules;

// Make the common module relative to the docroot, for symlink'd index.php
if ( ! is_dir($common) AND is_dir(DOCROOT.$common))
    $common = DOCROOT.$common;

// Make the system relative to the docroot, for symlink'd index.php
if ( ! is_dir($system) AND is_dir(DOCROOT.$system))
    $system = DOCROOT.$system;


// Define the absolute paths for configured directories
define('APPPATH', realpath($application).DIRECTORY_SEPARATOR);
define('KOMODPATH', realpath($komodules).DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules).DIRECTORY_SEPARATOR);
define('COMMONPATH', realpath($common).DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system).DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($application, $modules, $komodules,$common, $system);

// OC install
if (file_exists(DOCROOT.'install/install.lock'))
{
    // Load the installation check
    return include DOCROOT.'install/index'.EXT;
}


/**
 * Define the start time of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_TIME'))
{
    define('KOHANA_START_TIME', microtime(TRUE));
}

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_MEMORY'))
{
    define('KOHANA_START_MEMORY', memory_get_usage());
}

// Bootstrap the application
require APPPATH.'bootstrap'.EXT;

if ( ! defined('SUPPRESS_REQUEST'))
{
    /**
     * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
     * If no source is specified, the URI will be automatically detected.
     */
    echo Request::factory(TRUE, array(),FALSE)
        ->execute()
        ->send_headers()
        ->body();
}
