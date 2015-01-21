<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="clear"></div>

<footer>
    <div class="container">
        <?=(Theme::get('footer_ad')!='')?Theme::get('footer_ad'):''?>
        <?if(Core::config('appearance.theme_mobile')!=''):?>
        <hr>
        <nav class="pages">
            <ul>
                <li>
                    <a href="<?=Route::url('default')?>?theme=<?=Core::config('appearance.theme_mobile')?>"><?=__('Mobile Version')?></a>
                </li>
            </ul>
        </nav>
        <?endif?>
        <!--This is the license for Open Classifieds, do not remove -->
        <p>&copy;
        <?if (Theme::get('premium')!=1):?>
            Powered by <a href="http://open-eshop.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script to sell digital goods Software">Open eShop</a> 
        <?else:?>
            <?=core::config('general.site_name')?> <?=date('Y')?>
        <?endif?>    
        </p>
    </div>
</footer>
