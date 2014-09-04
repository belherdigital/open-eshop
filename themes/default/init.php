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

// use CDN or local files
$use_cdn = Core::use_cdn_for_css_js();

$theme_css = array(
    $use_cdn?'//netdna.bootstrapcdn.com/bootswatch/3.2.0/yeti/bootstrap.min.css':'css/yeti-bootstrap.3.2.0.min.css' => 'screen',
    $use_cdn?'//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css':'css/font-awesome.4.1.0.min.css' => 'screen',
    $use_cdn?'//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css':'css/chosen.1.1.0.min.css' => 'screen',
    $use_cdn?'//cdn.jsdelivr.net/prettyphoto/3.1.5/css/prettyPhoto.css':'css/prettyPhoto.3.1.5.css' => 'screen',
    'css/style.css?v='.Core::VERSION => 'screen',
    'css/yeti-style.css' => 'screen', // custom style
    'css/slider.css' => 'screen',
);
if(Theme::get('rtl'))
    $theme_css = array_merge($theme_css, array('css/bootstrap-rtl.min.css' => 'screen'));

Theme::$styles = $theme_css;

Theme::$scripts['footer'] = array(
    $use_cdn?'//code.jquery.com/jquery-1.10.2.min.js':'js/jquery-1.10.2.min.js',
    $use_cdn?'//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js':'js/bootstrap.3.2.0.min.js',
    $use_cdn?'//cdn.jsdelivr.net/prettyphoto/3.1.5/js/jquery.prettyPhoto.js':'js/jquery.prettyPhoto.3.1.5.js',
    $use_cdn?'//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js':'css/chosen.1.1.0.jquery.min.js' => 'screen',
    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'chosen')),
    'js/bootstrap-slider.js',
    $use_cdn?'//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js':'js/jquery.validate.1.13.0.min.js', // @TODO Localization Files at http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/localization/messages_##.js where ## is the loc code
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