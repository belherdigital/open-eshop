<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->
<head>
	<meta charset="<?=Kohana::$charset?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>
    <meta name="keywords" content="<?=$meta_keywords?>" >
    <meta name="description" content="<?=$meta_description?>" >
    <meta name="copyright" content="<?=$meta_copywrite?>" >
	<meta name="author" content="open-classifieds.com">
	<meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=Theme::styles($styles,'default')?>	
	<?=Theme::scripts($scripts,'header','default')?>
    <link rel="shortcut icon" href="<?=Theme::public_path('img/favicon.ico')?>">

	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
      .error{color:#ff1a1a;}

      .thumb_market{
        height: 200px; width: 300px;
        }

        .market_item{
          height:450px;
        }

        /* collapsable categories selector*/
        .btn.btn-primary.btn-mini.collapsed {
          display: inline-block !important;
        }
        .accordion-group {
          border: none;
          -webkit-border-radius: none;
          -moz-border-radius: none;
          border-radius: none;
        }
        .accordion-inner { border-top: none;}


    @media screen and (max-width: 979px) {
        body { padding-top:0; }
        .navbar .nav { float:none; }
        .navbar .nav > li { border:0; }
    }
    </style>

  </head>

  <body>
	<?=$header?>
    <div class="container">
	    <div class="row">
	    
		<?=View::factory('oc-panel/sidebar',array('user'=>$user))?>
			<div class="span9">
				<?=Breadcrumbs::render('oc-panel/breadcrumbs')?>      
				<?=Alert::show()?>
				<?=$content?>
	    	</div><!--/span--> 
	    	
		</div><!--/row-->
		<?=$footer?>
    </div><!--/.fluid-container-->

	<?=Theme::scripts($scripts,'footer','default')?>

	<!--[if lt IE 7 ]>
		<?=HTML::script('http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js')?>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->
  <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
  </body>
</html>

