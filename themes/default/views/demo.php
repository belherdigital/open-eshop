<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->
<head>
    <meta charset="<?=Kohana::$charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?=__('Demo')?> - <?=$title?></title>
    <meta name="keywords" content="<?=__('Demo')?>,<?=$meta_keywords?>" >
    <meta name="description" content="<?=__('Demo')?> - <?=$meta_description?>" >
    <meta name="copyright" content="<?=$meta_copywrite?>" >
    <meta name="author" content="open-eshop.com">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="alternate" type="application/atom+xml" title="RSS <?=Core::config('general.site_name')?>" href="<?=Route::url('rss')?>" />

    <?if (Controller::$category!==NULL):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=Core::config('general.site_name')?> - <?=Controller::$category->name?>"  href="<?=Route::url('rss',array('category'=>Controller::$category->seoname))?>" />
    <?endif?>     
    
    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.0.2/flatly/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        iframe{
        position: absolute; 
        background: transparent; 
        width: 100%; 
        height:100%; 
        top: 0; 
        padding-top:60px; 
        z-index: 1;
        display:block;
        border:none;
        }
        .btn-header-group{padding-top: 10px;}
        
    </style>

    <link rel="shortcut icon" href="<?=Theme::public_path('img/favicon.ico')?>">

    <?if ( core::config('general.analytics')!='' AND Kohana::$environment === Kohana::PRODUCTION ): ?>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?=Core::config('general.analytics')?>']);
      _gaq.push(['_setDomainName', '<?=$_SERVER['SERVER_NAME']?>']);
      _gaq.push(['_setAllowLinker', true]);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script> 
    <?endif?>
    
    </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only"><?=__('Toggle Navigation')?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button> 
          <a class="navbar-brand" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
            <span class="glyphicon glyphicon-th-large"></span> <?=substr($product->title, 0, 30)?>
          </a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">

            <?if (count($skins)>0):?>
            <li class="active" class="dropdown">
              <a title="<?=__('Choose stlye')?>" href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?=($skin!=NULL)?$skin:__('Choose style')?><b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <?foreach ($skins as $s):?>
                    <?if ($s!=$skin):?>
                    <li><a title="<?=$s?>" href="<?=Route::url('demo', array('seotitle'=>$product->seotitle))?>?skin=<?=$s?>"><?=$s?></a></li>
                    <?endif?>
                <?endforeach?>
              </ul>
            </li>
            <?endif?>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=__('More themes')?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?foreach ($products as $p):?>
                    <?if (!empty($p->url_demo) AND $p->id_product!=$product->id_product):?>
                    <li><a title="<?=__('Demo')?> - <?=$p->title?>" href="<?=Route::url('demo', array('seotitle'=>$p->seotitle))?>"><?=$p->title?></a></li>
                    <?endif?>
                <?endforeach?>
                
              </ul>
            </li>

            <li><p class="navbar-text"><?=substr(Text::removebbcode($product->description), 0, 30)?></p></li>
          </ul>

          <div class="btn-group navbar-right btn-header-group">

                <a class="btn btn-success" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                <?if ($product->final_price()>0):?>
                    <?=__('Buy Now')?> <?=$product->final_price().' '.$product->currency?>
                <?elseif(!empty($product->file_name)):?>
                    <?=__('Free Download')?>
                <?else:?>
                    <?=__('Get it for Free')?>
                <?endif?>
                </a> 
                <a class="btn btn-info" title="<?=__('Full screen demo')?>" href="<?=$product->url_demo?>&skin=<?=$skin?>">
                    <span class="glyphicon glyphicon-fullscreen"></span> 
                </a>
            </div>

            
        </div><!--/.nav-collapse -->

    </div>



    <iframe frameborder="0" noresize="noresize" src="<?=$product->url_demo?>&skin=<?=$skin?>" ></iframe>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
  </body>
</html>