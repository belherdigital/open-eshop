<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->
<head>
	<meta charset="<?=Kohana::$charset?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?if (core::config('general.disallowbots')=='1'):?>
		<meta name="robots" content="noindex,nofollow,noodp,noydir" /><meta name="googlebot" content="noindex,noarchive,nofollow,noodp" /><meta name="slurp" content="noindex,nofollow,noodp" /><meta name="bingbot" content="noindex,nofollow,noodp,noydir" /><meta name="msnbot" content="noindex,nofollow,noodp,noydir" />
	<?endif?>

	<title><?=$title?></title>
    <meta name="keywords" content="<?=$meta_keywords?>" >
    <meta name="description" content="<?=HTML::chars($meta_description)?>" >
    <meta name="copyright" content="<?=HTML::chars($meta_copyright)?>" >
	<meta name="author" content="open-eshop.com">
	<meta name="viewport" content="width=device-width,initial-scale=1">

    <?if (isset($product)):?>
    <link rel="canonical" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" />
    <?endif?>

    <link rel="alternate" type="application/atom+xml" title="RSS <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss')?>" />

    <?if (Model_Category::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname))?>" />
    <?endif?>     
        
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 7]><link rel="stylesheet" href="https://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    
    <?=Theme::styles($styles)?>	
	<?=Theme::scripts($scripts)?>

    <link rel="shortcut icon" href="<?=core::config('general.base_url').'images/favicon.ico'?>">

    <?=View::factory('analytics')?>
    
    </head>

    <body>


    <div class="container" >
        <section class="centered-box" id="page">
            <?=Alert::show()?>
            <?=$content?>
        </section>
    </div>

    <?if (!Auth::instance()->logged_in()):?>
    <div id="login-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <a class="close" data-dismiss="modal" >&times;</a>
                  <h3><?=__('Login')?></h3>
                </div>
                
                <div class="modal-body">
                    <?=View::factory('pages/auth/login-form')?>
                </div>
            </div>
        </div>
    </div>
    
    <div id="forgot-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <a class="close" data-dismiss="modal" >&times;</a>
                  <h3><?=__('Forgot password')?></h3>
                </div>
                
                <div class="modal-body">
                    <?=View::factory('pages/auth/forgot-form')?>
                </div>
            </div>
        </div>
    </div>
    
     <div id="register-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <a class="close" data-dismiss="modal" >&times;</a>
                  <h3><?=__('Register')?></h3>
                </div>
                
                <div class="modal-body">
                    <?=View::factory('pages/auth/register-form')?>
                </div>
            </div>
        </div>
    </div>
    <?endif?>


	<?=Theme::scripts($scripts,'footer')?>
	
  <?//=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
  </body>
</html>
