<?php defined('SYSPATH') or die('No direct script access.');?>
<header class="navbar navbar-default navbar-fixed-top bs-docs-nav">
    <div class="header-container">
        <div class="navbar-header">        </div> 

            <button class="navbar-toggle pull-left" type="button" data-toggle="collapse" id="mobile_header_btn">
                <span class="sr-only"><?=__('Toggle navigation')?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <a class="navbar-brand ajax-load" href="<?=(Auth::instance()->get_user()->id_role!=Model_Role::ROLE_ADMIN) ? Route::url('oc-panel',array('controller'=>'profile','action'=>'index')) : Route::url('oc-panel',array('controller'=>'home'))?>">
                <i class="glyphicon glyphicon-th-large"></i> <?=__('Panel')?>
            </a>
            <div class="btn-group pull-right ml-20">
                <?=View::factory('oc-panel/widget_login')?>
            </div>

            <div class="navbar-collapse collapse" id="mobile-menu-panel">
                <ul class="nav navbar-nav">

                    <?if (Theme::get('premium')!=1):?>
                        <?=Theme::admin_link(__('Market'), 'market','index','oc-panel','glyphicon glyphicon-gift')?>
                    <?endif?>

                    <?if (!Auth::instance()->get_user()->has_access_to_any('supportadmin')):?>
                        <?=Theme::admin_link(__('Support'), 'support','index','oc-panel','glyphicon glyphicon-comment')?>
                    <?else:?>
                        <?=Theme::admin_link(__('Support Admin'), 'support','index','oc-panel','glyphicon glyphicon-comment','admin')?>
                        <?=Theme::admin_link(__('Support Assigned'), 'support','index','oc-panel','glyphicon glyphicon-comment','assigned')?>
                    <?endif?>

                	<?=Theme::admin_link(__('Stats'),'stats','index','oc-panel','glyphicon glyphicon-align-left')?>
                    <?=Theme::admin_link(__('Widgets'),'widget','index','oc-panel','glyphicon glyphicon-move')?>

                    <?if (Auth::instance()->get_user()->has_access_to_any('tools')):?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-cog"></i> <?=__('Cache')?> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?=Theme::admin_link(__('Cache'),'tools','cache','oc-panel','glyphicon glyphicon-cog')?>
                            <li>
                                <a class="ajax-load" title="<?=__('Delete all')?>" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1'?>">
                                    <i class="glyphicon glyphicon-remove-sign"></i> <span class="side-name-link"><?=__('Delete all')?></span>
                                </a>
                            </li>
                            <li>
                                <a class="ajax-load" title="<?=__('Delete expired')?>" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=2'?>">
                                    <i class="glyphicon glyphicon-remove-circle"></i> <span class="side-name-link"><?=__('Delete expired')?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?endif?>

                    <? if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
            	    <li  class="dropdown "><a href="#" class="dropdown-toggle"
            		      data-toggle="dropdown"><i class="glyphicon glyphicon-plus"></i> <?=__('New')?> <b class="caret"></b></a>
                    	<ul class="dropdown-menu">
                            <?=Theme::admin_link(__('Product'),'product','create')?>
                            <?=Theme::admin_link(__('Blog post'),'blog','create')?>
                            <?=Theme::admin_link(__('FAQ'),'content','create?type=help&locale_select='.core::config('i18n.locale'),'oc-panel')?>
                            <?=Theme::admin_link(__('Page'), 'content','create?type=page&locale_select='.core::config('i18n.locale'),'oc-panel')?>
                    	</ul>
            	   </li> 
                   <?endif?>

                </ul>
                
                <div class=""></div>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?=Route::url('default')?>">
                                <i class="  glyphicon-home glyphicon"></i>
                            <?=_('Visit Site')?>
                        </a>
                    </li>
                </ul>

            </div> <!--/.nav-collapse -->

    </div><!-- /.header-container -->

</header><!--/.navbar -->
