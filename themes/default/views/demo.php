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
    <meta name="description" content="<?=HTML::chars($meta_description)?>" >
    <meta name="copyright" content="<?=HTML::chars($meta_copyright)?>" >
    <meta name="author" content="open-eshop.com">
    <?if (Controller::$image!==NULL):?>
    <meta property="og:image"   content="<?=core::config('general.base_url').Controller::$image?>" />
    <?endif?>
    <meta property="og:title"   content="<?=HTML::chars($title)?>" />
    <meta property="og:description"   content="<?=HTML::chars($meta_description)?>" />
    <meta property="og:url"     content="<?=URL::current()?>" />
    <meta property="og:site_name" content="<?=HTML::chars(core::config('general.site_name'))?>" />
    
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="alternate" type="application/atom+xml" title="RSS <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss')?>" />

    <?if (Model_Category::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname))?>" />
    <?endif?>     
    
    <!-- Bootstrap core CSS -->
    <link href="//cdn.jsdelivr.net/bootswatch/3.3.6/<?=core::config('product.demo_theme')?>/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/fontawesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        iframe{
            background: transparent; 
            width: 100%; 
            height:100%; 
            top: 0; 
            z-index: 1;
            display:block;
            border:none;
            margin:0 auto;
            max-width:100%;
        }
        .btn-header-group{padding-top: 5px;}
        body{background-color: grey}
        .switcher-bar{height:50px !important;}
        
        .multi-column-dropdown {
            -webkit-column-count:3;
            -moz-column-count:3;
            -ms-column-count:3;
            -o-column-count:3;
            column-count:3;
            columns:3;
            width:505px;
        }
        
        .multi-column-dropdown li {
            display: inline-block;
            width:160px
        }

        .desktop-view{
            padding-top:50px;
        }

        .tablet-border{
            margin-top:150px;
            border-radius: 36px 36px 36px 36px;
            -moz-border-radius: 36px 36px 36px 36px;
            -webkit-border-radius: 36px 36px 36px 36px;
            border-left: 56px solid #000000;
            border-right: 56px solid #000000;
            border-bottom: 36px solid #000000;
            border-top: 36px solid #000000;
        }
        .mobile-border{
            margin-top:75px;
            border-radius: 24px 24px 24px 24px;
            -moz-border-radius: 24px 24px 24px 24px;
            -webkit-border-radius: 24px 24px 24px 24px;
            border-left: 16px solid #000000;
            border-right: 16px solid #000000;
            border-bottom: 80px solid #000000;
            border-top: 60px solid #000000;
        }
        .qr_align{
            margin: 30px 0 30px 130px;
        }
    </style>

    <link rel="shortcut icon" href="<?=core::config('general.base_url').'images/favicon.ico'?>">

    <?=View::factory('analytics')?>
    
    </head>

  <body>

    <script type="text/javascript">
        //dont allow be inside an iframe
        if (window!=window.top) { window.top.location.href = window.location.href; }
    </script>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top switcher-bar" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only"><?=__('Toggle Navigation')?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button> 
          <a class="navbar-brand" href="<?=Route::url('default')?>">
            <img src="<?=core::config('general.base_url').'images/favicon.ico'?>" alt="<?=HTML::chars(core::config('general.site_name'))?>" /> 
          </a>
          <a class="navbar-brand" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
            <?=$product->title?>
          </a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">

            <?if ($products->count() > 1):?>
            <li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=__('Other products')?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?foreach ($products as $p):?>
                    <?if ($p->id_product!=$product->id_product):?>
                    <li><a title="<?=__('Demo')?> - <?=$p->title?>" href="<?=Route::url('product-demo', array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname))?>"><?=$p->title?></a></li>
                    <?endif?>
                <?endforeach?>
              </ul>
            </li>
            <?endif?>

            <?if (($total_skins = count($skins)) > 0):?>
            <li class="dropdown">
              <a title="<?=__('Choose style')?>" href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?=($skin!=NULL)?$skin:__('Choose style')?> (<?=(count($skins))?>)<b class="caret"></b>
              </a>
              <ul class="dropdown-menu <?=($total_skins > 10) ? 'multi-column-dropdown' : NULL?>">
                <?foreach ($skins as $s):?>
                    <?if ($s != $skin):?>
                    <li><a title="<?=HTML::chars($s)?>" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>?skin=<?=$s?>"><?=$s?></a></li>
                    <?endif?>
                <?endforeach?>
              </ul>
            </li>
            <?endif?>

            <li><p class="navbar-text"><?=Text::limit_chars(Text::removebbcode($product->description), 45, NULL, TRUE)?></p></li>
          </ul>

          <div class="btn-group navbar-right btn-header-group">
                <?if (core::config('product.demo_resize')==TRUE):?>
                <a class="btn btn-default btn-sm mobile-btn" title="Mobile" href="#">
                    <span class="fa fa-mobile fa-2x"></span> 
                </a>
                <a class="btn btn-default btn-sm tablet-btn" title="Tablet" href="#">
                    <span class="fa fa-tablet fa-2x"></span> 
                </a>
                <a class="btn btn-default btn-sm desktop-btn" title="Desktop full width" href="#">
                    <span class="fa fa-desktop fa-2x"></span> 
                </a>
                <a type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#qrCode" title="<?=__('Scan QR and check out the template on your phone or tablet')?>" href="#">
                    <span class="fa fa-qrcode fa-2x"></span>
                </a>
                <?endif?>
                <a class="btn btn-success btn-sm" 
                    <?if (Valid::url($product->url_buy)):?>
                    href="<?=$product->url_buy?>"
                    <?else:?>
                    href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"
                    <?endif?>
                    title="<?if ($product->final_price()>0):?>
                    <?=__('Buy Now')?> <?=$product->formated_price()?>
                    <?elseif($product->has_file()==TRUE):?><?else:?><?=__('Get it for Free')?><?endif?>">
                    <span class="fa fa-shopping-cart fa-2x"></span>
                </a> 
                <a class="btn btn-default btn-sm" title="<?=__('Full screen demo, removes the bar')?>" href="<?=$product->url_demo?><?=(count($skins)>0)?'&skin='.$skin:''?>">
                    <span class="fa fa-times fa-2x"></span> 
                </a>
            </div>

            
        </div><!--/.nav-collapse -->
    </div>

    <?if (core::config('product.demo_resize')==TRUE):?>
    <!-- Modal for the QR-->
    <div class="modal fade" id="qrCode" tabindex="-1" role="dialog" aria-labelledby="qrCodeLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="qrCodeLabel"><?=__('Scan QR and check out the template on your phone or tablet')?></h4>
          </div>
          <div class="modal-body">
            <div class="qr_align">
            <?=core::generate_qr($product->url_demo.'&skin='.$skin, 300)?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?endif?>

    <iframe class="desktop-view" id="product-iframe" frameborder="0" noresize="noresize" src="<?=$product->url_demo?><?=(count($skins)>0)?'&skin='.$skin:''?>" ></iframe>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        /* Modified from
         * Switcheroo by OriginalEXE
         * https://github.com/OriginalEXE/Switcheroo
         * MIT licenced
         */
        $productIframe = $( '#product-iframe' );

        // Let's calculate iframe height
        function switcher_iframe_height() {
                var $w_height = $( window ).height(),$b_height = $( '.switcher-bar' ).height(),
                $i_height = $w_height - $b_height - 2;
                $productIframe.height($i_height);
        }

        $( document ).ready(switcher_iframe_height);

        // Switching views
        $( '.desktop-btn' ).on( 'click', function() {
            $productIframe.removeClass();
            $productIframe.addClass('desktop-view');
            $productIframe.animate({'width'       : $( window ).width(), });
            switcher_iframe_height();
            return false;
        });

        $( '.tablet-btn' ).on( 'click', function() {
            $productIframe.removeClass();
            $productIframe.addClass('tablet-border');
            $productIframe.animate({'width'  : '1024px','height' : '768px'});
            return false;
        });

        $( '.mobile-btn' ).on( 'click', function() {
            $productIframe.removeClass();
            $productIframe.addClass('mobile-border');
            $productIframe.animate({'width'  : '360px','height' : '720px' });
            return false;
        }); 

    </script>
  </body>
</html>
