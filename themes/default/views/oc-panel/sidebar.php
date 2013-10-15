<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3 hidden-phone">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
                <li class="divider"></li>
				
                <?Theme::admin_link(__('Advertisements'),'ad','index','oc-panel','icon-th-large')?>
                <? if(core::config('general.moderation') == 1 OR // moderation on  
                      core::config('general.moderation') == 4 OR // email confiramtion with moderation
                      core::config('general.moderation') == 5):  // payment with moderation?>
				<?Theme::admin_link(__('Moderation'),'ad','moderate','oc-panel','icon-ban-circle')?>
                <? endif?>
				<?Theme::admin_link(__('Categories'),'category','index','oc-panel','icon-tags')?>
				<?Theme::admin_link(__('Locations'),'location','index','oc-panel','icon-map-marker')?>
				<?Theme::admin_link(__('Orders'), 'order','index','oc-panel','icon-shopping-cart')?>
                <? if($user->id_role==10):?><li class="divider"></li><?endif?>

				<?Theme::admin_link(__('Content'), 'content','index','oc-panel','icon-file')?>
                <?Theme::admin_link(__('Translations'), 'translations','index','oc-panel','icon-globe')?>
                <?Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel','icon-envelope')?>
                <? if($user->id_role==10):?><li class="divider"></li><?endif?>

                <?Theme::admin_link(__('Themes'), 'theme','index','oc-panel','icon-picture')?>
                <?if (Theme::has_options()) 
                        Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel','icon-wrench')?>     
                <?Theme::admin_link(__('Widgets'), 'widget','index','oc-panel','icon-move')?>   
                <?Theme::admin_link(__('Custom Fields'), 'fields','index','oc-panel','icon-plus-sign')?>       
                <?Theme::admin_link(__('Market'), 'market','index','oc-panel','icon-gift')?>
                <? if($user->id_role==10):?><li class="divider"></li><?endif?>

			<?if ($user->has_access_to_any('settings,config')):?>
				<li class="nav-header dropdown-submenu <?=(in_array(Request::current()->controller(),array('settings','config'))) ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-edit"></i><?=__('Settings')?></a>
                    <ul class="dropdown-menu">
    				    <?Theme::admin_link(__('General'), 'settings','general')?>
    				    <?Theme::admin_link(__('Payment'), 'settings','payment')?>
    				    <?Theme::admin_link(__('Email'), 'settings','email')?>
    				    <?Theme::admin_link(__('Advertisement'), 'settings','form')?>
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
                        <?Theme::admin_link(__('Migration'), 'tools','migration')?>
                        <?Theme::admin_link(__('Optimize'), 'tools','optimize')?>
                        <?Theme::admin_link(__('Cache'), 'tools','cache')?>
                        <?Theme::admin_link(__('Logs'), 'tools','logs')?>
                        <?Theme::admin_link(__('PHP Info'), 'tools','phpinfo')?>
                    </ul>
                </li>
			<?endif?>

			<? if($user->has_access_to_any('profile') AND $user->id_role!=10):?>
				<li class="nav-header"><i class="icon-user"></i><?=__('Profile')?></li>
				<?Theme::admin_link(__('Edit profile'), 'profile','edit')?>
                <?Theme::admin_link(__('My Advertisements'), 'profile','ads')?>
                <?Theme::admin_link(__('Stats'),'profile','stats')?>
                <?Theme::admin_link(__('Subscriptions'),'profile','subscriptions')?>
				<li><a
					href="<?=Route::url('profile',array('seoname'=>$user->seoname))?>">
					<?=__('Public profile')?>
				</a>
				</li>
			<?endif?>

			<?if (Theme::get('premium')!=1):?>
			<li class="divider"></li>
			<li class="nav-header">Open eShop</li>
			<li><a href="http://open-classifieds.com/?utm_source=<?=URL::base()?>&utm_medium=oc_sidebar&utm_campaign=<?=date('Y-m-d')?>"><?=__('Home')?></a></li>
			<li><a href="http://open-classifieds.com/contact/?utm_source=<?=URL::base()?>&utm_medium=oc_sidebar&utm_campaign=<?=date('Y-m-d')?>"><?=__('Contact')?></a></li>
            <li class="divider"></li>
			<li><script type="text/javascript">if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
                document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.com/sync.js?a=6&f=3&w=200&h=200\"></scr"+"ipt>");
                </script>
            </li>
			
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
