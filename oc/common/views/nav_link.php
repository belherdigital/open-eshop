<?php defined('SYSPATH') or die('No direct script access.');?>
<li title="<?=HTML::chars($route)?>" class="<?=(strtolower(Request::current()->controller())==$controller AND Request::current()->action()==$action)?'active':''?> <?=$style?>" >
    <a  href="<?=Route::url($route,array('controller'=>$controller,'action'=>$action,'id'=>$id))?>">
        <?if($icon!==NULL):?>
            <i class="<?=$icon?>"></i>
        <?endif?>
        <?=$name?>
    </a>
</li>