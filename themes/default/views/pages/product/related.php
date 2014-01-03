<?php defined('SYSPATH') or die('No direct script access.');?>

<?if(count($products)):?>
<h3><?=__('Related products')?></h3>
<ul class="media-list">
    <?foreach($products as $p ):?>
    <li class="media">
        <?if($p->get_first_image() !== NULL):?>
        <a class="pull-left" title="<?= $p->title;?>" href="<?=Route::url('product', array('controller'=>'product','category'=>$p->category->seoname,'seotitle'=>$p->seotitle))?>">
            <img class="media-object" width="64px" height="64px" src="<?=URL::base()?><?=$p->get_first_image()?>" alt="<?= $p->title?>" >
        </a>
        <?endif?>
        <div class="media-body">
            <h4 class="media-heading">
                <?if($p->featured >= Date::unix2mysql(time())):?>
                    <span class="label label-danger pull-right"><?=__('Featured')?></span>
                <?endif?>
                <a title="<?= $p->title;?>" href="<?=Route::url('product', array('controller'=>'product','category'=>$p->category->seoname,'seotitle'=>$p->seotitle))?>"> <?=$p->title; ?></a>
            </h4>
            <p><?=substr(Text::removebbcode($p->description),0, 255);?></p>
        </div>
    </li>
    <?endforeach?>
</ul>
<?endif?>