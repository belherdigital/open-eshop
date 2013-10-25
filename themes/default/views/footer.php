<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="clear"></div>

<footer>
    <div class="container">
        <?=(Theme::get('footer_ad')!='')?Theme::get('footer_ad'):''?>
        <hr>
        <nav class="pages">
            <ul>
                <?if(Core::config('appearance.theme_mobile')!=''):?>
                <li>
                    <a href="<?=Route::url('default')?>?theme=<?=Core::config('appearance.theme_mobile')?>"><?=__('Mobile Version')?></a>
                </li>
                <?endif?>
                <?
                    $pages = new Model_Content();
                    $pages = $pages ->select('seotitle','title')
                        ->where('type','=', 'page')
                        ->where('status','=', 1)
                        ->order_by('order','asc')
                        ->cached()
                        ->find_all();
                ?>
                <?foreach ($pages as $page):?>
                <li><a href="<?=Route::url('page',array('seotitle'=>$page->seotitle))?>" title="<?=$page->title?>">
                    <?=$page->title?></a>
                </li>
                <?endforeach?>
            </ul>
        </nav>
        <p>&copy;
<?if (Theme::get('premium')!=1):?>
    Web Powered by <a href="http://open-eshop.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script to sell digital goods Classifieds Software">Open eShop</a> 
    2009 - <?=date('Y')?>
<?else:?>
    <?=core::config('general.site_name')?> <?=date('Y')?>
<?endif?>   </p>
    </div>
</footer>
