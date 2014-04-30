<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return 
   array( 
      // "base_url" the url that point to HybridAuth Endpoint (where index.php and config.php are found) 
      "base_url" => core::config('general.base_url').'social/loggin/', 
 
      "providers" => array ( 
      ),
      
      "debug_mode" => TRUE , 
      
      // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file 
      "debug_file" => Kohana::find_file('vendor', 'hybridauth/logs','txt'), 
    ); 