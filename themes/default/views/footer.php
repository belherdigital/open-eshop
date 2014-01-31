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
        <p>&copy; <?=core::config('general.site_name')?> <?=date('Y')?></p>
    </div>
</footer>
