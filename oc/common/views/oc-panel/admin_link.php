<?php defined('SYSPATH') or die('No direct script access.');?>
<li <?=(strtolower(Request::current()->controller())==$controller)?'class="active"':''?>>
    <a href="<?=Route::url($route,array('controller'=>$controller,'action'=>$action,'id'=>$id))?>" 
    	title="<?=HTML::chars($name)?>" 
    	class="<?=($ajax)?'ajax-load':NULL?>">
        <?if($icon!==NULL):?>
            <i class="<?=$icon?>"></i>
        <?endif?>
        <span class="side-name-link"><?=$name?></span>
    </a>
</li>