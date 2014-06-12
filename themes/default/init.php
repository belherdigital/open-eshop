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


//local files
    $theme_css = array(
                        'http://netdna.bootstrapcdn.com/bootswatch/3.1.1/yeti/bootstrap.min.css' => 'screen',
                        'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.css' => 'screen',
                        'http://cdn.jsdelivr.net/prettyphoto/3.1.6/css/prettyPhoto.css' => 'screen',
                        'css/style.css?v='.Core::VERSION => 'screen',
                        'css/yeti-style.css' => 'screen',
                        'css/slider.css' => 'screen',
                        );
    if(Theme::get('rtl'))
      $theme_css = array_merge($theme_css, array('css/bootstrap-rtl.min.css' => 'screen'));

    Theme::$styles = $theme_css;

    Theme::$scripts['footer']   = array('http://code.jquery.com/jquery-1.10.2.min.js',
                                        'http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
                                        'http://cdn.jsdelivr.net/prettyphoto/3.1.6/js/jquery.prettyPhoto.js',
                                        'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.jquery.min.js',
                                        'js/bootstrap-slider.js',
                                        'js/jquery.validate.min.js',
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