<?php 
/**
 * HTML template for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//were the install files are located
define('INSTALLROOT', DOCROOT.'install/');

//we check first short tags if not we can not even load the installer
if (! ((bool) ini_get('short_open_tag')) )
        die('<strong><u>OE Installation requirement</u></strong>: Before you proceed with your OE installation: Keep in mind OE uses the short tag "short cut" syntax.<br><br> Thus the <a href="http://php.net/manual/ini.core.php#ini.short-open-tag" target="_blank">short_open_tag</a> directive must be enabled in your php.ini.<br><br><u>Easy Solution</u>:<ol><li>Open php.ini file and look for line short_open_tag = Off</li><li>Replace it with short_open_tag = On</li><li>Restart then your PHP server</li><li>Refresh this page to resume your OE installation</li><li>Enjoy OE ;)</li></ol>');

//prevents from new install to be done
if(!file_exists(INSTALLROOT.'install.lock')) 
    die('Installation seems to be done, please remove /install/ folder');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

include 'class.install.php';

//start the install setup
install::initialize();
$is_compatible = install::is_compatible();

//choosing what to display
//execute installation since they are posting data
if ( ($_POST OR isset($_GET['SITE_NAME'])) AND $is_compatible === TRUE)
    $view = (install::execute()===TRUE)?'success':'form';
//normally if its compaitble just display the form
elseif ($is_compatible === TRUE)
    $view = 'form';
//not compatible
else
    $view = 'hosting';
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Open eShop <?=__("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="Open eShop <?=install::VERSION?>" >
    <meta name="author" content="Open eShop">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="http://open-eshop.com/wp-content/uploads/2014/10/favicon.ico" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>    <![endif]-->
       
    <style type="text/css">
    body {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .sidebar-nav {
        padding: 9px 0;
    }
    .chosen-single{padding: 4px 0px 27px 8px!important;}
    .chosen-single b{margin: 4px!important;}
    .navbar-brand{padding: 4px 50px 0px 0px!important;}
    .we-install{padding: 5px!important;margin-top: 7px;}
    .adv{display: none;}
    .logo img {margin-top: 30px;}
    .page-header{margin: 25px 0 21px!important;}
    .mb-10{margin-bottom: 10px!important;}
    #myTab{margin-top: 14px;}

    </style>
        
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.3.0/yeti/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css">

</head>

<body>
    <div class="container">
        <div class="navbar navbar-fixed-top navbar-inverse">

            <div class="navbar-inner">
                <div class="container">
                    <button class="navbar-toggle pull-left" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                        <span class="sr-only"><?=__("Toggle navigation")?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-collapse bs-navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#home" data-toggle="tab"><?=__("Install")?></a></li>
                            <li><a href="http://market.open-eshop.com/faq/" target="_blank"><?=__("FAQ")?></a></li>
                            <li><a href="#requirements" data-toggle="tab"><?=__("Requirements")?></a></li>
                            <li><a href="#about" data-toggle="tab"><?=__("About")?></a></li>
                        </ul>

                        <div class="btn-group pull-right">
                            <a class="btn btn-success we-install" href="http://open-eshop.com/market/">
                                <i class="glyphicon-shopping-cart glyphicon"></i> <?=__("We install it for you, Buy now!")?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <a class="logo" target="_blank" href="http://open-eshop.com">
            <img src="http://open-eshop.com/wp-content/themes/strappress-eshop/img/logo.png" width="249" height="45" alt="Open eShop <?=__("Installation")?>">
        </a>    
        <div class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <?install::view($view)?>
            </div>
            <div class="tab-pane fade" id="requirements">
                <?install::view('requirements')?>
            </div>
            <div class="tab-pane fade" id="about">
                <?install::view('about')?>
            </div>
        </div>
           
        <hr>

        <footer>
            <p>
            &copy;  <a href="http://open-eshop.com" title="Open Source PHP digital goods">Open eShop</a> 2013 - <?=date('Y')?>
            </p>
        </footer>
    </div> 
    
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.bootstrapvalidation/1.3.7/jqBootstrapValidation.min.js"></script>
    <script src="//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js"></script>

    <script>
        $(function () { 
            $("select").chosen();
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); 
            $('input, select').tooltip(); 
        });

        $('#advanced-options').click(function(){
            if($(this).hasClass('btn-primary'))
            {
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-default');
                $('.adv').each(function(){
                    $(this).hide();
                });
                $('#myTab').css('display','none');
            }
            else
            {
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
                $('.adv').each(function(){
                    $(this).show();
                });
                $('#myTab').css('display','block');  
            }
        });

        $('#phpinfobutton').click(function(){
            if($('#phpinfo').hasClass('hidden'))
            {
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-default');
                $('#phpinfo').removeClass('hidden');
            }
            else
            {
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
                $('#phpinfo').addClass('hidden');
            }
        });

    </script>

</body>
</html>