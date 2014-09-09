<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Kamaleon Free
  * Description: Free theme, HTML5. Default base, link on footer.
  * Tags: HTML5, Advanced Confiuration, prettyPhoto, Slider.
  * Version: 1.6
  * Author: Chema <chema@open-classifieds.com>
  * License: GPL v3
  */


/**
 * placeholders & widgets for this theme
 */
Widgets::$theme_placeholders    = array('header','sidebar','footer');


/**
 * custom options for the theme
 * @var array
 */
Theme::$options = Theme::get_options();

//we load earlier the theme since we need some info
Theme::load(); 


//external resources
Theme::$styles = array(
    Core::get_external_resource('bootswatch','yeti')['css'] => 'screen',
    Core::get_external_resource('chosen')['css'] => 'screen',
    Core::get_external_resource('prettyphoto')['css'] => 'screen',
    'css/style.css?v='.Core::VERSION => 'screen',
    'css/yeti-style.css' => 'screen',
    'css/slider.css' => 'screen',
);
if(Theme::get('rtl'))
    Theme::$styles = array_merge(Theme::$styles, array(Core::get_external_resource('bootstrap.rtl')['css'] => 'screen'));

Theme::$scripts['footer'] = array(
    Core::get_external_resource('jquery')['js'],
    Core::get_external_resource('bootstrap')['js'],
    Core::get_external_resource('prettyphoto')['js'],
    Core::get_external_resource('chosen')['js'],
    'js/bootstrap-slider.js',
    Core::get_external_resource('jquery.validate')['js'],
    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'validate')),
    'js/theme.init.js?v='.Core::VERSION,
);


/**
 * custom error alerts
 */
Form::$errors_tpl   = '<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>
                        <h4 class="alert-heading">%s</h4>
                        <ul>%s</ul></div>';

Form::$error_tpl    = '<div class="alert"><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl     =   '<div class="alert alert-%s">
                    <a class="close" data-dismiss="alert" href="#">×</a>
                    <h4 class="alert-heading">%s</h4>%s
                    </div>';
