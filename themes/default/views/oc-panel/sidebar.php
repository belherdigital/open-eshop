<?php defined('SYSPATH') or die('No direct script access.');?>

<aside class="col-md-1 col-sm-1 col-xs-1 respon-left-panel">
    <div class="sidebar-nav">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-sm-1 col-xs-1 respon-left-panel">
                    <div class="panel-group" id="accordion">
                    <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                                    </span> <span class="title-txt">eShop</span></a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Products'), 'product','index','oc-panel','glyphicon glyphicon-inbox')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Categories'),'category','index','oc-panel','glyphicon glyphicon-tags')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Orders'), 'order','index','oc-panel','glyphicon glyphicon-shopping-cart')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Coupons'), 'coupon','index','oc-panel','glyphicon glyphicon-tag')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Downloads'), 'download','index','oc-panel','glyphicon glyphicon-download-alt')?></td></tr>
                                        <?if (core::config('product.reviews')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Reviews'), 'review','index','oc-panel','glyphicon glyphicon-star-empty')?></td></tr>
                                        <?endif?>
                                        <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Support Admin'), 'support','index','oc-panel','glyphicon glyphicon-question-sign','admin')?></td></tr>
                                            <tr><td class="br"><?=Theme::admin_link(__('Support Assigned'), 'support','index','oc-panel','glyphicon glyphicon-question-sign','assigned')?></td></tr>
                                        <?endif?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-folder-open">
                                    </span> <span class="title-txt"><?=__('Content')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <?if (core::config('general.blog')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel','glyphicon glyphicon-pencil')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Page'), 'content','list?type=page&locale_select='.core::config('i18n.locale'),'oc-panel','glyphicon glyphicon-file')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'content','list?type=email&locale_select='.core::config('i18n.locale'),'oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <?if (core::config('general.faq')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('FAQ'), 'content','list?type=help&locale_select='.core::config('i18n.locale'),'oc-panel',' glyphicon glyphicon-question-sign')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel','glyphicon glyphicon-globe')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <? if($user->id_role==Model_Role::ROLE_ADMIN AND core::config('general.forums')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Forums'),'forum','index','oc-panel','glyphicon glyphicon-tags')?></td></tr>
                                            <tr><td class="br"><?=Theme::admin_link(__('Topics'), 'topic','index','oc-panel','glyphicon glyphicon-pencil')?></td></tr>
                                        <?endif?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-picture">
                                    </span> <span class="title-txt"><?=__('Appearance')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Themes'), 'theme','index','oc-panel','glyphicon glyphicon-picture')?></td></tr>
                                        <?if (Theme::has_options()):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel','glyphicon  glyphicon-wrench')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Widgets'), 'widget','index','oc-panel','glyphicon glyphicon-move')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Menu'), 'menu','index','oc-panel','glyphicon glyphicon-list')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Social Auth'), 'social','index','oc-panel','glyphicon glyphicon-thumbs-up')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?if ($user->has_access_to_any('settings,config')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseSettings"><span class="glyphicon glyphicon-wrench">
                                    </span> <span class="title-txt"><?=__('Settings')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseSettings" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('General'), 'settings','general')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Payment'), 'settings','payment')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'settings','email')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Product'), 'settings','product')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <?if ($user->has_access_to_any('user,role,access')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseUser"><span class="glyphicon glyphicon-user">
                                    </span> <span class="title-txt"><?=__('Users')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseUser" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table no-hide">
                                        <tr><td class="br"><?=Theme::admin_link(__('Users'),'user')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('User Roles'),'role')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Roles access'),'access')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <?if ($user->has_access_to_any('user,role,access')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTools"><span class="glyphicon glyphicon-filter">
                                    </span> <span class="title-txt"><?=__('Tools')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseTools" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Updates'), 'update','index')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Sitemap'), 'tools','sitemap')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Migration'), 'tools','migration')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Optimize'), 'tools','optimize')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Cache'), 'tools','cache')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Logs'), 'tools','logs')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('PHP Info'), 'tools','phpinfo')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                    <?endif?>
                        <? if($user->has_access_to_any('profile')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-align-justify">
                                    </span> <span class="title-txt"><?=__('Profile Options')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Purchases'), 'profile','orders','oc-panel','glyphicon glyphicon-shopping-cart')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Support'), 'support','index','oc-panel','glyphicon glyphicon-comment')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Edit profile'), 'profile','edit','oc-panel','glyphicon glyphicon-user')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a  class=" btn-colapse-sidebar"><span class="glyphicon glyphicon-circle-arrow-left"></span>
                                    <span class="title-txt"><?=__('Collapse menu')?></span>
                                    </a>
                                </h4>
                            </div>
                        </div>
                        <?if (Theme::get('premium')!=1):?>
                        <div class="panel panel-default no-prem">
                            <div class="panel-heading">
                                <a href="http://open-eshop.com/?utm_source=<?=URL::base()?>&utm_medium=oc_sidebar&utm_campaign=<?=date('Y-m-d')?>">by Open eShop</a>
                                <a href="https://twitter.com/openeshop"
                                    onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
                                    class="twitter-follow-button" data-show-count="false"
                                    data-size="large">Follow @openeshop</a><br />
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            </div>
                        </div>
                        <?endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
