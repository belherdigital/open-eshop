<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3 hidden-phone">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <li class="divider"></li>
                <?endif?>
				<?Theme::admin_link(__('Products'), 'product','index','oc-panel','icon-inbox')?>
				<?Theme::admin_link(__('Categories'),'category','index','oc-panel','icon-tags')?>
				<?Theme::admin_link(__('Orders'), 'order','index','oc-panel','icon-shopping-cart')?>
                <?Theme::admin_link(__('Licenses'), 'license','index','oc-panel','icon-list')?>
                <?Theme::admin_link(__('Coupons'), 'coupon','index','oc-panel','icon-tag')?>
                <?Theme::admin_link(__('Downloads'), 'download','index','oc-panel','icon-download-alt')?>
                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <?Theme::admin_link(__('Support Admin'), 'support','index','oc-panel','icon-question-sign','admin')?>
                    <?Theme::admin_link(__('Support Assigned'), 'support','index','oc-panel','icon-question-sign','assigned')?>
                    <li class="divider"></li>
                <?endif?>

                <?if (core::config('general.blog')==1):?>
                    <?Theme::admin_link(__('Blog'), 'blog','index','oc-panel','icon-pencil')?>
                <?endif?>
				<?Theme::admin_link(__('Page'), 'content','list?type=page&locale_select='.core::config('i18n.locale'),'oc-panel','icon-file')?>
                <?Theme::admin_link(__('Email'), 'content','list?type=email&locale_select='.core::config('i18n.locale'),'oc-panel','icon-envelope')?>
                <?Theme::admin_link(__('Translations'), 'translations','index','oc-panel','icon-globe')?>
                <?Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel','icon-envelope')?>
                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <li class="divider"></li>
                <?endif?>

                <?Theme::admin_link(__('Themes'), 'theme','index','oc-panel','icon-picture')?>
                <?if (Theme::has_options()) 
                        Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel','icon-wrench')?>     
                <?Theme::admin_link(__('Widgets'), 'widget','index','oc-panel','icon-move')?>
                <?Theme::admin_link(__('Menu'), 'menu','index','oc-panel','icon-list')?>   
                <?Theme::admin_link(__('Social Auth'), 'social','index','oc-panel','icon-thumbs-up')?>
                <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                    <li class="divider"></li>
                <?endif?>

			<?if ($user->has_access_to_any('settings,config')):?>
				<li class="nav-header dropdown-submenu <?=(in_array(Request::current()->controller(),array('settings','config'))) ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-edit"></i><?=__('Settings')?></a>
                    <ul class="dropdown-menu">
    				    <?Theme::admin_link(__('General'), 'settings','general')?>
    				    <?Theme::admin_link(__('Payment'), 'settings','payment')?>
    				    <?Theme::admin_link(__('Email'), 'settings','email')?>
    				    <?Theme::admin_link(__('Product'), 'settings','product')?>
                    </ul>
                </li>
			<?endif?>

            <?if ($user->has_access_to_any('user,role,access')):?>
                <li class="nav-header dropdown-submenu <?=(in_array(Request::current()->controller(),array('user','role','access'))) ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-user"></i><?=__('Users')?></a>
                    <ul class="dropdown-menu">
                      <?Theme::admin_link(__('Users'),'user')?>
                      <?Theme::admin_link(__('User Roles'),'role')?>
                      <?Theme::admin_link(__('Roles access'),'access')?>
                    </ul>
                </li>
            <? endif ?>

			<?if ($user->has_access_to_any('tools')):?>
				<li class="nav-header dropdown-submenu <?=(Request::current()->controller()=='tools') ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-wrench"></i><?=__('Tools')?></a>
                    <ul class="dropdown-menu">
                        <?Theme::admin_link(__('Updates'), 'update','index')?>
                        <?Theme::admin_link(__('Sitemap'), 'tools','sitemap')?>
                        <?Theme::admin_link(__('Optimize'), 'tools','optimize')?>
                        <?Theme::admin_link(__('Cache'), 'tools','cache')?>
                        <?Theme::admin_link(__('Logs'), 'tools','logs')?>
                        <?Theme::admin_link(__('Import Orders'), 'order','import')?>
                        <?Theme::admin_link(__('PHP Info'), 'tools','phpinfo')?>
                    </ul>
                </li>
			<?endif?>

			<? if($user->has_access_to_any('profile')):?>
				<li class="divider"></li>
                <?Theme::admin_link(__('Purchases'), 'profile','orders','oc-panel','icon-shopping-cart')?>
                <?Theme::admin_link(__('Support'), 'support','index','oc-panel','icon-comment')?>
                <?Theme::admin_link(__('Edit profile'), 'profile','edit','oc-panel','icon-user')?>
			<?endif?>

			<?if (Theme::get('premium')!=1):?>
			<li class="divider"></li>
			<li class="nav-header">by Open eShop</li>
			<li><a href="http://open-eshop.com/?utm_source=<?=URL::base()?>&utm_medium=oc_sidebar&utm_campaign=<?=date('Y-m-d')?>">Open eShop</a></li>
            <li class="divider"></li>

            <li><a href="https://twitter.com/openclassifieds"
                onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
                class="twitter-follow-button" data-show-count="false"
                data-size="large">Follow @openclassifieds</a><br />
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
			<?endif?>
		</ul>
        
	</div>
	<!--/.well -->
</div>
<!--/span-->
