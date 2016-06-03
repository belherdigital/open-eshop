<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->languages_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->languages_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?=i18n::get_display_language(i18n::$locale)?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?foreach($widget->languages as $language):?>
            <?if(i18n::$locale!=$language):?>
            <li>
                <a href="<?=Route::url('default')?>?language=<?=$language?>">
                <?=i18n::get_display_language($language)?></a>
            </li>
            <?endif?>
            <?endforeach?>
        </ul>
    </div>
</div>