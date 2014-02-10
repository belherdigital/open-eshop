<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Kamaleon Free
  * Description: Free theme, HTML5. Default base, link on footer.
  * Tags: HTML5, Advanced Confiuration, prettyPhoto, Slider.
  * Version: 1.3
  * Author: Chema <chema@garridodiaz.com>
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

    Theme::$styles = array(
                        'http://netdna.bootstrapcdn.com/bootswatch/3.0.3/yeti/bootstrap.min.css' => 'screen',
                        'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.css' => 'screen',
                        'http://cdn.jsdelivr.net/prettyphoto/3.1.5/css/prettyPhoto.css' => 'screen',
                        'css/style.css?v=1.3' => 'screen',
                        'css/yeti-style.css' => 'screen',
                        'css/slider.css' => 'screen',
                        );

    Theme::$scripts['footer']   = array('http://code.jquery.com/jquery-1.10.2.min.js',
                                        'http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js',
                                        'http://cdn.jsdelivr.net/prettyphoto/3.1.5/js/jquery.prettyPhoto.js',
                                        'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.jquery.min.js',
                                        'js/bootstrap-slider.js',
                                        'js/jquery.validate.min.js',
                                        'js/theme.init.js?v=1.3',
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


/**
 * Theme Functions
 * 
 */


/**
 * nav_link generates a link for main nav-bar
 * @param  string $name       translated name in the A
 * @param  string $controller
 * @param  string $action  
 * @param  string $icon         class name of bootstrap icon to append with nav-link   
 * @param  string $route      
 * @param  string $style extra class div 
 */
function kam_link($name, $controller, $icon=NULL, $action='index', $route='default' , $style = NULL)
{   
    
    ?>
        <li alt="<?=$route?>" title="<?=$route?>" class="<?=(Request::current()->controller()==$controller 
                && Request::current()->action()==$action)?'active':''?> <?=$style?>" >
            <a href="<?=Route::url($route,array('controller'=>$controller,
                                                'action'=>$action))?>">
                <?if($icon!==NULL)?>
                    <i class="<?=$icon?>"></i>
                <?=$name?>
            </a>
        </li>
    <?
}