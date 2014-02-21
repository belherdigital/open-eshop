<?php defined('SYSPATH') or die('No direct script access.');?>
<aside class="col-md-1 col-sm-1 col-xs-1 respon-left-panel well">
    
    <div class="sidebar-nav">
        
        <!-- <button type="button" class="btn btn-default miniclose pull-right"><span class="glyphicon glyphicon-arrow-left"></span></button> -->
        <div class="clearfix"></div>
        
        <ul class="nav nav-list side-ul active">
				<?=Theme::admin_link(__('Products'), 'product','index','oc-panel','glyphicon glyphicon-inbox')?>
				<?=Theme::admin_link(__('Categories'),'category','index','oc-panel','glyphicon glyphicon-tags')?>
				<?=Theme::admin_link(__('Orders'), 'order','index','oc-panel','glyphicon glyphicon-shopping-cart')?>
                <?=Theme::admin_link(__('Licenses'), 'license','index','oc-panel','glyphicon glyphicon-list')?>
                <?=Theme::admin_link(__('Coupons'), 'coupon','index','oc-panel','glyphicon glyphicon-tag')?>
                <?=Theme::admin_link(__('Downloads'), 'download','index','oc-panel','glyphicon glyphicon-download-alt')?>
                <?if (core::config('product.reviews')==1):?>
                    <?=Theme::admin_link(__('Reviews'), 'review','index','oc-panel','glyphicon glyphicon-star-empty')?>
                <?endif?>
                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <?=Theme::admin_link(__('Support Admin'), 'support','index','oc-panel','glyphicon glyphicon-question-sign','admin')?>
                    <?=Theme::admin_link(__('Support Assigned'), 'support','index','oc-panel','glyphicon glyphicon-question-sign','assigned')?>
                    <li class="divider"></li>
                <?endif?>

                <?if (core::config('general.blog')==1):?>
                    <?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel','glyphicon glyphicon-pencil')?>
                <?endif?>
				<?=Theme::admin_link(__('Page'), 'content','list?type=page&locale_select='.core::config('i18n.locale'),'oc-panel','glyphicon glyphicon-file')?>
                <?=Theme::admin_link(__('Email'), 'content','list?type=email&locale_select='.core::config('i18n.locale'),'oc-panel','glyphicon glyphicon-envelope')?>
                <?if (core::config('general.faq')==1):?>
                    <?=Theme::admin_link(__('FAQ'), 'content','list?type=help&locale_select='.core::config('i18n.locale'),'oc-panel',' glyphicon glyphicon-question-sign')?>
                <?endif?>
                
                <?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel','glyphicon glyphicon-globe')?>
                <?=Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel','glyphicon glyphicon-envelope')?>

                <? if($user->id_role==Model_Role::ROLE_ADMIN AND core::config('general.forums')==1):?>
                    <li class="divider"></li>
                    <?=Theme::admin_link(__('Forums'),'forum','index','oc-panel','glyphicon glyphicon-tags')?>
                    <?=Theme::admin_link(__('Topics'), 'topic','index','oc-panel','glyphicon glyphicon-pencil')?>
                <?endif?>

                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <li class="divider"></li>
                <?endif?>

                <?=Theme::admin_link(__('Themes'), 'theme','index','oc-panel','glyphicon glyphicon-picture')?>
                <?if (Theme::has_options()) 
                        Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel','glyphicon glyphicon-wrench')?>     
                <?=Theme::admin_link(__('Widgets'), 'widget','index','oc-panel','glyphicon glyphicon-move')?>
                <?=Theme::admin_link(__('Menu'), 'menu','index','oc-panel','glyphicon glyphicon-list')?>   
                <?=Theme::admin_link(__('Social Auth'), 'social','index','oc-panel','glyphicon glyphicon-thumbs-up')?>
                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <li class="divider"></li>
                <?endif?>

			<?if ($user->has_access_to_any('settings,config')):?>
                <li class="dropdown-sidebar sbp <?=(in_array(Request::current()->controller(),array('settings','config'))) ?'active':''?>">
                <a class="dropdown-toggle"><i class="glyphicon glyphicon-edit"></i><span class="side-name-link"><?=__('Settings')?><i class="glyphicon glyphicon-chevron-down pull-right"></i></span></a>
                    <ul class="submenu">
                        <?=Theme::admin_link(__('General'), 'settings','general')?>
                        <?=Theme::admin_link(__('Payment'), 'settings','payment')?>
                        <?=Theme::admin_link(__('Email'), 'settings','email')?>
                        <?=Theme::admin_link(__('Product'), 'settings','product')?>
                    </ul>
                </li>
            <?endif?>

            <?if ($user->has_access_to_any('user,role,access')):?>
                <li class="dropdown-sidebar sbp <?=(in_array(Request::current()->controller(),array('user','role','access'))) ?'active':''?>">
                <a class="dropdown-toggle"><i class="glyphicon glyphicon-user"></i><span class="side-name-link"><?=__('Users')?><i class="glyphicon glyphicon-chevron-down pull-right"></i></span></a>
                    <ul class="submenu">
                      <?=Theme::admin_link(__('Users'),'user')?>
                      <?=Theme::admin_link(__('User Roles'),'role')?>
                      <?=Theme::admin_link(__('Roles access'),'access')?>
                    </ul>
                </li>
            <? endif ?>

            <?if ($user->has_access_to_any('tools')):?>
                <li class="dropdown-sidebar sbp <?=(Request::current()->controller()=='tools') ?'active':''?>">
                <a class="dropdown-toggle"><i class="glyphicon glyphicon-wrench"></i><span class="side-name-link"><?=__('Tools')?><i class="glyphicon glyphicon-chevron-down pull-right"></i></span></a>
                    <ul class="submenu">
                        <?=Theme::admin_link(__('Updates'), 'update','index')?>
                        <?=Theme::admin_link(__('Sitemap'), 'tools','sitemap')?>
                        <?=Theme::admin_link(__('Migration'), 'tools','migration')?>
                        <?=Theme::admin_link(__('Optimize'), 'tools','optimize')?>
                        <?=Theme::admin_link(__('Cache'), 'tools','cache')?>
                        <?=Theme::admin_link(__('Logs'), 'tools','logs')?>
                        <?=Theme::admin_link(__('PHP Info'), 'tools','phpinfo')?>
                    </ul>
                </li>
            <?endif?>

			<? if($user->has_access_to_any('profile')):?>
				<li class="divider"></li>
                <?=Theme::admin_link(__('Purchases'), 'profile','orders','oc-panel','glyphicon glyphicon-shopping-cart')?>
                <?=Theme::admin_link(__('Support'), 'support','index','oc-panel','glyphicon glyphicon-comment')?>
                <?=Theme::admin_link(__('Edit profile'), 'profile','edit','oc-panel','glyphicon glyphicon-user')?>
			<?endif?>
            <div class="divider"></div>
            <li>
                <a  class=" btn-colapse-sidebar"><i class="glyphicon glyphicon-circle-arrow-left"></i>
                <span class="side-name-link"><?=__('Collapse menu')?></span>
                </a>
                
            </li>
			<?if (Theme::get('premium')!=1):?>
			<li class="divider"></li>
			<li><a href="http://open-eshop.com/?utm_source=<?=URL::base()?>&utm_medium=oc_sidebar&utm_campaign=<?=date('Y-m-d')?>">by Open eShop</a></li>
            <li><a href="https://twitter.com/openeshop"
                onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
                class="twitter-follow-button" data-show-count="false"
                data-size="large">Follow @openeshop</a><br />
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
			<?endif?>
		</ul>
        
	</div>
	<!--/.well -->
</aside>
<!--/span-->
