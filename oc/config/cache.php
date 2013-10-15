<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    'default' => 'file',
    
    'file'  => array
    (
        'driver'             => 'file',
        'cache_dir'          => APPPATH.'cache/',
        'default_expire'     => 3600,
        'ignore_on_delete'   => array(),
    ),
    
    'apc'      => array(
        'driver'             => 'apc',
        'default_expire'     => 3600,
    ),
);