<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->

<head>
<?=View::factory('header_metas',array('title'             => $title,
                                      'meta_keywords'     => $meta_keywords,
                                      'meta_description'  => $meta_description,
                                      'meta_copyright'    => $meta_copyright,))?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 7]><link rel="stylesheet" href="//blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    <?=Theme::styles($styles)?> 
    <?=Theme::scripts($scripts)?>
    <?=core::config('general.html_head')?>
    <?=View::factory('analytics')?>
</head>

    <body data-spy="scroll" data-target=".subnav" data-offset="50" class="<?=((Request::current()->controller()!=='faq') AND Theme::get('fixed_toolbar')==1)?'':'body_fixed'?>">
    
    <?=View::factory('alert_terms')?>
    
	<?=$header?>
    
    <div class="container bs-docs-container" id="main">
    <div class="alert alert-warning off-line" style="display:none;"><strong><?=__('Warning')?>!</strong> <?=__('We detected you are currently off-line, please login to gain full experience.')?></div>
        <div class="row">

            <?=(Theme::get('sidebar_position')=='left')?View::fragment('sidebar_front','sidebar'):''?>

            <section class="col-lg-9" id="page">
                <?=(Theme::get('breadcrumb')==1)?Breadcrumbs::render('breadcrumbs'):''?>
                <?=Alert::show()?>

                <div class="row">
                    <?foreach ( Widgets::render('header') as $widget):?>
                    <div class="col-lg-9">
                        <?=$widget?>
                    </div>
                    <?endforeach?>
                </div>

                <?=(Theme::get('header_ad')!='')?Theme::get('header_ad'):''?>
                <?=$content?>
            </section>

            <?=(Theme::get('sidebar_position')=='right')?View::fragment('sidebar_front','sidebar'):''?>
            
            <div class="container">
                <?foreach ( Widgets::render('footer') as $widget):?>
                <div class="col-lg-3">
                    <?=$widget?>
                </div>
                <?endforeach?>
            </div>


            <?=$footer?>
       </div>     
    </div><!--/.fluid-container-->


	<?=Theme::scripts($scripts,'footer')?>
	<?=core::config('general.html_footer')?>
	
  <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
  </body>
</html>
