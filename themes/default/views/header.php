<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="navbar navbar-default <?=((Request::current()->controller()!=='faq') AND Theme::get('fixed_toolbar')==1)?'navbar-fixed-top':''?>">
    <div class="container resiz-cont">

        <div class="btn-group pull-right btn-header-group">
            <?=View::factory('widget_login')?>         
        </div>
        
        <div class="navbar-collapse bs-navbar-collapse collapse" id="mobile-menu-panel">
            <ul class="nav navbar-nav">
            <?if (class_exists('Menu') AND count( $menus = Menu::get() )>0 ):?>
                <?foreach ($menus as $menu => $data):?>
                    <li class="<?=(Request::current()->uri()==$data['url'])?'active':''?>" >
                    <a href="<?=$data['url']?>" target="<?=$data['target']?>">
                        <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i> <?endif?>
                        <?=$data['title']?></a> 
                    </li>
                <?endforeach?>
            <?else:?>
                <li class="<?=(Request::current()->controller()=='home')?'active':''?>" >
                    <a href="<?=Route::url('default')?>"><i class="glyphicon glyphicon-home "></i> <?=__('Home')?></a> </li>
                <?=Theme::nav_link(__('Listing'),'ad', 'glyphicon glyphicon-list ' ,'listing', 'list')?>
                <?if (core::config('general.blog')==1):?>
                    <?=Theme::nav_link(__('Blog'),'blog','glyphicon glyphicon-file','index','blog')?>
                <?endif?>
                <?if (core::config('general.faq')==1):?>
                    <?=Theme::nav_link(__('FAQ'),'faq','glyphicon glyphicon-question-sign','index','faq')?>
                <?endif?>
                <?if (core::config('general.forums')==1):?>
                    <?=Theme::nav_link(__('Forums'),'forum','glyphicon glyphicon-tag','index','forum-home')?>
                <?endif?>
                <?=Theme::nav_link(__('Search'),'ad', 'glyphicon glyphicon-search ', 'advanced_search', 'search')?>
                <?if (core::config('advertisement.map')==1):?>
                    <?=Theme::nav_link('','map', 'glyphicon glyphicon-globe ', 'index', 'map')?>
                <?endif?>
                <?=Theme::nav_link(__('Contact'),'contact', 'glyphicon glyphicon-envelope ', 'index', 'contact')?>
                <?=Theme::nav_link('','rss', 'glyphicon glyphicon-signal ', 'index', 'rss')?>
            </ul>
        <?endif?>
            
        </div>

        
        <!--/.nav-collapse --> 
    </div>
    <!-- end container--> 
</div>

<div id="logo">
    <div class="container">
    <div class="row">
        <div class="col-lg-8">
            <a class="brand" href="<?=Route::url('default')?>" title="<?=HTML::chars(core::config('general.site_name'))?>">

                <?if (Theme::get('logo_url')!=''):?>
                    <img src="<?=Theme::get('logo_url')?>" alt="<?=HTML::chars(core::config('general.site_name'))?>" >
                <?else:?>
                    <h1><?=core::config('general.site_name')?></h1>
                <?endif?>
            </a>
        </div>
        <?if (Theme::get('short_description')!=''):?>
        <div class="col-lg-8">
            <p class="lead"><?=Theme::get('short_description')?></p>
        </div>
        <?endif?>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-30 pull-right">
            <?= FORM::open(Route::url('search'), array('class'=>'navbar-form '.(Theme::get('short_description')!='')?'no-margin':'', 
                'method'=>'GET', 'action'=>''))?>
                <input type="text" name="search" class="form-control col-lg-3 col-md-3 col-sm-12 col-xs-12" placeholder="<?=__('Search')?>">
            <?= FORM::close()?>
        </div>
        </div>
    </div>
</div>

<!-- end navbar top-->
<div class="subnav navbar <?=((Request::current()->controller()!=='faq') AND Theme::get('fixed_toolbar')==1)?'':'fixed_header'?>">
  <div class="container">

    <ul class="nav nav-pills">
        <?
            $cats = Model_Category::get_category_count();

            $cat_id = NULL;
            $cat_parent = NULL;

            if (Model_Category::current()->loaded())
            {
                $cat_id = Model_Category::current()->id_category;
                $cat_parent =  Model_Category::current()->id_category_parent;
            }
        ?>
        <?foreach($cats as $c ):?>
            <?if($c['id_category_parent'] == 1 && $c['has_siblings'] == FALSE):?>
                <li  class="<?=($c['id_category'] == $cat_id)?'active':''?>"> 
                    <a title="<?=HTML::chars($c['name'])?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>">
                        <?=$c['name']?> </a>
                </li>
            <?elseif($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
                <li class="dropdown <?=($c['id_category'] == $cat_parent OR $c['id_category'] == $cat_id)?'active':''?>">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" title="<?=HTML::chars($c['name'])?>" >
                        <?=$c['name']?><b class="caret"></b></a>

                    <ul class="dropdown-menu">                          
                    <?foreach($cats as $chi):?>
                    <?if($chi['id_category_parent'] == $c['id_category']):?>
                        <li class="<?=($chi['id_category'] == $cat_id)?'active':''?>" >
                            <a title="<?=HTML::chars($chi['name'])?>" href="<?=Route::url('list', array('category'=>$chi['seoname']))?>">
                            <?=$chi['name']?> <span class="badge pull-right"><?=$chi['count']?></span></a>
                        </li>
                    <?endif?>
                    <?endforeach?>
                    <li class="divider"></li>
                    <li><a title="<?=HTML::chars($c['name'])?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>">
                        <?=$c['name']?> <span class="badge badge-success pull-right"><?=$c['count']?></span></a></li>
                    </ul>
                    
                </li>
            <?endif?>
        <?endforeach?>
    </ul>
    <!-- end nav-pills--> 
    <div class="clear"></div>
   </div> <!-- end container-->
  </div><!-- end .subnav-->


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
                    <?=View::factory('pages/auth/register-form', ['recaptcha_placeholder' => 'recaptcha4'])?>
                </div>
            </div>
        </div>
    </div>
<?endif?>