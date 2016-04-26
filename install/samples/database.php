<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    'default' => array(
        'type'       => 'mysqli',
        'connection' => array(
            'hostname'   => '[DB_HOST]',
            'username'   => '[DB_USER]',
            'password'   => '[DB_PASS]',
            'persistent' => FALSE,
            'database'   => '[DB_NAME]',
            'variables'  => array('sql_mode'=>'')
            ),
        'table_prefix' => '[TABLE_PREFIX]',
        'charset'      => '[DB_CHARSET]',
        'profiling'    => (Kohana::$environment===Kohana::DEVELOPMENT)? TRUE:FALSE,
     ),
);