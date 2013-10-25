<?php defined('SYSPATH') or die('No direct access allowed.');


/**
 * custom options for the theme
 * @var array
 */
return array(  
                            
                'fixed_toolbar' => array(   'type'      => 'text',
                                            'display'   => 'select',
                                            'label'     => __('Header tool bar gets fixed in the top'),
                                            'options'   => array(   '1' => __('Yes'),
                                                                    '0'  => __('No'),
                                                                ), 
                                            'default'   => '1',
                                            'required'  => TRUE),

                'breadcrumb' => array(   'type'      => 'text',
                                            'display'   => 'select',
                                            'label'     => __('Display breadcrumb'),
                                            'options'   => array(   '1' => __('Yes'),
                                                                    '0'  => __('No'),
                                                                ), 
                                            'default'   => '1',
                                            'required'  => TRUE),

                'logo_url' => array(   'type'      => 'text',
                                            'display'   => 'text',
                                            'label'     => __('URL to your Logo. Recommended size 250px x 40px. Leave blank for none.'),
                                            'default'   => '',),
                'short_description' => array(   'type'      => 'text',
                                            'display'   => 'text',
                                            'label'     => __('Short description that appears after the site name.'),
                                            'default'   => ''),

                'num_home_products' => array(   'type'      => 'text',
                                            'display'   => 'text',
                                            'label'     => __('Numbers of products to display in home slider'),
                                            'default'   => 21,
                                            'required'  => TRUE),

                'sidebar_position' => array(   'type'      => 'text',
                                            'display'   => 'select',
                                            'label'     => __('Where you want the sidebar to appear'),
                                            'options'   => array(   'right' => __('Right side'),
                                                                    'left'  => __('Left side'),
                                                                ), 
                                            'default'   => 'right',
                                            'required'  => TRUE),
);