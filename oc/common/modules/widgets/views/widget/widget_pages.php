<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->page_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->page_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <ul>
        <?foreach($widget->page_items as $page):?>
            <?if (core::config('general.contact_page')!=$page->seotitle AND core::config('general.private_site_page')!=$page->seotitle): ?>
                <li><a href="<?=Route::url('page',array('seotitle'=>$page->seotitle))?>" title="<?=HTML::chars($page->title)?>">
                    <?=$page->title?></a>
                </li>
            <?endif?>
        <?endforeach?>
    </ul>
</div>