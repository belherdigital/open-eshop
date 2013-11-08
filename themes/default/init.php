<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Kamaleon Free
  * Description: Responsive theme, HTML5. Default base, link on footer.
  * Tags: HTML5, Responsive, Mobile, Advanced Confiuration, prettyPhoto, Slider.
  * Version: 1.1
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

$theme_css = array( 'http://netdna.bootstrapcdn.com/bootswatch/2.3.2/flatly/bootstrap.min.css' => 'screen',
                    'http://cdn.jsdelivr.net/bootstrap/2.3.2/css/bootstrap-responsive.min.css' => 'screen',
                    'css/style.css?v=1.1' => 'screen',
                    'http://cdn.jsdelivr.net/chosen/0.9.12/chosen.css' => 'screen',
                    'css/slider.css' => 'screen',
                    'css/zocial.css' => 'screen',
                    'http://cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css' => 'screen', 
                    'http://cdn.jsdelivr.net/prettyphoto/3.1.5/css/prettyPhoto.css' => 'screen',
                    );

Theme::$styles = $theme_css;

Theme::$scripts['header']   = array('http://code.jquery.com/jquery-1.9.1.min.js',);
Theme::$scripts['footer']   = array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js',
                                    'js/jquery.sceditor.min.js',
                                    'http://cdn.jsdelivr.net/chosen/0.9.12/chosen.jquery.min.js',
                                    'js/bootstrap-slider.js',
                                    'js/jquery.validate.min.js',
                                    'http://cdn.jsdelivr.net/prettyphoto/3.1.5/js/jquery.prettyPhoto.js',
                                    'js/theme.init.js?v=1.1',
                                    );



/**
 * custom error alerts
 */
Form::$errors_tpl   = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>
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