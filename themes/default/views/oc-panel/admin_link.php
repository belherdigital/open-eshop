<?php defined('SYSPATH') or die('No direct script access.');?>
<li <?=(Request::current()->controller()==$controller)?'class="active"':''?> >
    <a href="<?=Route::url($route,array('controller'=>$controller,'action'=>$action,'id'=>$id))?>" title="<?=$name?>" class="ajax-load">
        <?if($icon!==NULL):?>
            <i class="<?=$icon?>"></i>
        <?endif?>
        <span class="side-name-link"><?=$name?></span>
    </a>
</li>