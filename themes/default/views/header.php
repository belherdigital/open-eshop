<?php defined('SYSPATH') or die('No direct script access.');?>

<header class="navbar <?=(Theme::get('fixed_toolbar')==1)?'navbar-fixed-top':'navbar-fixed-top fixed_header'?>">
  <div class="navbar-inner">
    <div class="container"> 

        <a class="btn btn-navbar" data-toggle="collapse" data-target= ".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a> 

        <div class="btn-group pull-right">
            <?=View::factory('widget_login')?>
        </div>

        <nav class="nav-collapse">
            <ul class="nav">
                <?if ( count( $menus = Menu::get() )>0 ):?>
                    <?foreach ($menus as $menu => $data):?>
                        <li class="<?=(Request::current()->uri()==$data['url'])?'active':''?>" >
                        <a href="<?=$data['url']?>" target="<?=$data['target']?>">
                            <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i> <?endif?>
                            <?=$data['title']?></a> 
                        </li>
                    <?endforeach?>
                <?else:?>
                    <?$icon_white=(in_array(Theme::$skin, array('journal','readable','simplex','spacelab','bootstrap')))?'':'icon-white'?>
                    <li class="<?=(Request::current()->controller()=='home')?'active':''?>" >
                        <a href="<?=Route::url('default')?>"><i class="icon-home <?=$icon_white?>"></i> <?=__('Home')?></a> </li>
                    <?kam_link(__('Listing'),'product', 'icon-list '.$icon_white ,'listing', 'list')?>
                    <?kam_link(__('Search'),'product', 'icon-search '.$icon_white, 'search', 'search')?>
                    <?if (core::config('general.blog')==1):?>
                        <?kam_link(__('Blog'),'blog','','index','blog')?>
                    <?endif?>
                    <?if (core::config('general.faq')==1):?>
                        <?kam_link(__('FAQ'),'faq','icon-question-sign '.$icon_white,'index','faq')?>
                    <?endif?>
                    <?kam_link(__('Contact'),'contact', 'icon-envelope '.$icon_white, 'index', 'contact')?>
                    <?kam_link('','rss', 'icon-signal '.$icon_white, 'index', 'rss')?>
                <?endif?>
            </ul>
        </nav>
        <!--/.nav-collapse --> 

    </div>
    <!-- end container--> 
    
  </div>
  <!-- end navbar-inner-->
  <div class="clear"></div>
</header>

<div id="logo">
  <div class="container">
    <div class="row">
      <div class="span8">
        <a class="brand" href="<?=Route::url('default')?>">

            <?if (Theme::get('logo_url')!=''):?>
                <img src="<?=Theme::get('logo_url')?>" title="<?=core::config('general.site_name')?>" alt="<?=core::config('general.site_name')?>" >
            <?else:?>
                <h1><?=core::config('general.site_name')?></h1>
            <?endif?>
        </a>
      </div>
      <?if (Theme::get('short_description')!=''):?>
      <div class="span8">
            <p class="lead"><?=Theme::get('short_description')?></p>
      </div>
      <?endif?>
      <div class="span4">
        <?= FORM::open(Route::url('search'), array('class'=>'navbar-search pull-right '.(Theme::get('short_description')!='')?'no-margin':'', 
            'method'=>'GET', 'action'=>''))?>
            <input type="text" name="search" class="search-query span3" placeholder="<?=__('Search')?>">
        <?= FORM::close()?>
      </div>
    </div>
  </div>
</div>

<!-- end navbar top-->
  <div class="subnav hidden-phone <?=(Theme::get('fixed_toolbar')==1)?'':'fixed_header'?>">
  <div class="container">

    <ul class="nav nav-pills">
        <?
            $cats = Model_Category::get_category_count();

            $cat_id = NULL;
            $cat_parent = NULL;
            if (Controller::$category!==NULL)
            {
                if (Controller::$category->loaded())
                {
                    $cat_id = Controller::$category->id_category;
                    $cat_parent =  Controller::$category->id_category_parent;
                }
            }

        ?>
        <?foreach($cats as $c ):?>
            <?if($c['id_category_parent'] == 1 && $c['has_siblings'] == FALSE):?>
                <li  class="<?=($c['id_category'] == $cat_id)?'active':''?>"> 
                    <a  title="<?=$c['seoname']?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>">
                        <?=$c['name']?> </a>
                </li>
            <?elseif($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
                <li class="dropdown <?=($c['id_category'] == $cat_parent OR $c['id_category'] == $cat_id)?'active':''?>">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" title="<?=$c['seoname']?>" >
                        <?=$c['name']?><b class="caret"></b></a>

                    <ul class="dropdown-menu">                          
                    <?foreach($cats as $chi):?>
                    <?if($chi['id_category_parent'] == $c['id_category']):?>
                        <li class="<?=($chi['id_category'] == $cat_id)?'active':''?>" >
                            <a title="<?=$chi['name']?>" href="<?=Route::url('list', array('category'=>$chi['seoname']))?>">
                            <?=$chi['name']?> <span class="badge pull-right"><?=$chi['count']?></span></a>
                        </li>
                    <?endif?>
                    <?endforeach?>
                    <li class="divider"></li>
                    <li><a   title="<?=$c['seoname']?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>">
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
	<div id="login-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Login')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/login-form')?>
    		</div>
    </div>
    
    <div id="forgot-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Forgot password')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/forgot-form')?>
    		</div>
    </div>
    
     <div id="register-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Register')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/register-form')?>
    		</div>
    </div>
<?endif?>