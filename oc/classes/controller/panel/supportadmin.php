<?php defined('SYSPATH') or die('No direct script access.');

/**
 * has nothing is just to be able to set permissions properly using $user->has_access('supportadmin'). so he can access support
 */
class Controller_Panel_Supportadmin extends Auth_Controller {}