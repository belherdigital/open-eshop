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
	<meta name="author" content="open-eshop.com">
	<meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=Theme::styles($styles,'default')?>   
    <?=Theme::scripts($scripts,'header','default')?>

	<style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }


        body.dragging, body.dragging * {
          cursor: move !important;
        }
        .dragged {
            position: absolute;
            top: 0;
            opacity: .5;
            z-index: 2000;
        }

        ul.plholder li.liholder{
            cursor: move !important;
            display: block;
            margin: 5px;
            padding: 5px;
            border: 1px solid #CCC;
            color: white;
            background: gray;
            width: 90%;
        }
    
        ul.plholder li.placeholder{
            position: relative;
            margin: 0;
            padding: 0;
            border: none;
        }

        ul.plholder li.placeholder:before {
            position: absolute;
            content: "";
            width: 0;
            height: 0;
            margin-top: -5px;
            left: -5px;
            top: -4px;
            border: 5px solid transparent;
            border-left-color: red;
            border-right: none;
            color: red;
        }

    </style>

  </head>

  <body>
	<?=$header?>
    <div class="container">
	    <div class="row">
	    	
	    	<div class="col-md-12">
	    		<?=Breadcrumbs::render('oc-panel/breadcrumbs')?>
	    		<?=Alert::show()?>
	    	</div><!--/span--> 

	    	<div class="col-md-8">
                <h2><?=__('Available widgets')?></h2>
                <a href="http://open-classifieds.com/2013/08/26/overview-of-widgets/" target="_blank"><?=__('Read more')?></a></a>

				<ul class="inline">
					<?foreach ($widgets as $widget):?>
						<?=$widget->form()?>
					<?endforeach?>
				</ul>                


	    	</div><!--/span--> 
	    	
	    	<!--placeholders-->
	    	<div class="col-md-4">
				<?foreach ($placeholders as $placeholder=>$widgets):?>
				<div class="well sidebar-nav">
                <p class="nav-header"><?=$placeholder?></p>
					<ul class="nav nav-list plholder" id="<?=$placeholder?>" >
                        <?foreach ($widgets as $widget):?>
                          <?=$widget->form()?>
                        <?endforeach?>
					</ul>
				</div>
				<?endforeach?>
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'saveplaceholders'))?>'></span>
			</div>
			<!--placeholders-->

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