<?php defined('SYSPATH') or die('No direct script access.');?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?=__('We are working on our site, please visit later. Thanks')?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

     <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=Theme::styles(array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css' => 'screen'),'default')?> 

  </head>

  <body>

    <div class="container">

        <div class="hero-unit">

            <h2><?=__('We are working on our site, please visit later. Thanks')?></h2>
     		
        </div>
        <a class="btn btn-mini" title="<?=__('Login')?>" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">
                <i class="icon-user"></i> 
                <?=__('Login')?>
            </a>    
    </div>
    
  </body>
</html>
