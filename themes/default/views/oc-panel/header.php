<?php defined('SYSPATH') or die('No direct script access.');?>
<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
    <div class="header-container">
        <div class="navbar-header">        </div> 

            <button class="navbar-toggle pull-left" type="button" data-toggle="collapse" id="mobile_header_btn">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <a class="navbar-brand" href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><i class="glyphicon glyphglyphicon glyphicon-th-large"></i> <?=__('Panel')?></a>
            <div class="btn-group pull-right ml-20">
                <?=View::factory('oc-panel/widget_login')?>
            </div>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <?=Theme::admin_link(__('Market'), 'market','index','oc-panel','glyphicon glyphicon-gift?v=2.1.2')?>
                    <?=Theme::admin_link(__('Support'), 'support','index','oc-panel','glyphicon glyphicon-comment?v=2.1.2')?>
                	<?=Theme::admin_link(__('Stats'),'stats','index','oc-panel','glyphicon glyphicon-align-left?v=2.1.2')?>
                    <?=Theme::admin_link(__('Widgets'),'widget','index','oc-panel','glyphicon glyphicon-move?v=2.1.2')?>
                    <?=Theme::admin_link(__('Cache'),'tools','cache','oc-panel','glyphicon glyphicon-cog?v=2.1.2')?>
                    <? if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
            	    <li  class="dropdown "><a href="#" class="dropdown-toggle"
            		      data-toggle="dropdown"><i class="glyphicon glyphicon-plus?v=2.1.2"></i> <?=__('New')?> <b class="caret"></b></a>
                    	<ul class="dropdown-menu">
                            
                            <?=Theme::admin_link(__('Product'),'product','create')?>
                            <?=Theme::admin_link(__('Blog post'),'blog','create')?>
                            <?=Theme::admin_link(__('FAQ'),'content','create?type=help&locale_select='.core::config('i18n.locale'),'oc-panel')?>
                            <?=Theme::admin_link(__('Page'), 'content','create?type=page&locale_select='.core::config('i18n.locale'),'oc-panel')?>
                    		
                    	</ul>
            	   </li> 
                   
                   <?endif?>

                </ul>
                
                <div class="nav pull-right">
                    <ul class="nav">
                        <li>
                            <a href="<?=Route::url('default')?>">
                                    <i class="glyphicon glyphicon-home?v=2.1.2"></i>
                                <?=_('Visit Site')?>
                            </a>
                        </li>
                    </ul>
                </div>

            </div> <!--/.nav-collapse -->

    </div><!-- /.header-container -->

</header><!--/.navbar -->