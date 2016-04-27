<?php defined('SYSPATH') or die('No direct script access.');?>

<?if(count($products)):?>
<h3><?=__('Related products')?></h3>
<ul class="media-list">
    <?foreach($products as $p ):?>
    <li class="media">
        <a class="pull-left" title="<?=HTML::chars($p->title)?>" href="<?=Route::url('product', array('controller'=>'product','category'=>$p->category->seoname,'seotitle'=>$p->seotitle))?>">
            <?if($p->get_first_image() !== NULL):?>
                <img class="media-object" width="64" height="64" src="<?=Core::imagefly(Core::S3_domain().$p->get_first_image(),64,64)?>" alt="<?=HTML::chars($p->title)?>">
            <?elseif(( $icon_src = $p->category->get_icon() )!==FALSE ):?>
                <img class="media-object" width="64" height="64" src="<?=Core::imagefly($icon_src,64,64)?>" alt="<?=HTML::chars($p->title)?>">
            <?else:?>
                <img src="//www.placehold.it/64x64&text=<?=$p->category->name?>" alt="<?=$p->title?>"> 
            <?endif?>
        </a>
        <div class="media-body">
            <h4 class="media-heading">
                <?if($p->featured >= Date::unix2mysql(time())):?>
                    <span class="label label-danger pull-right"><?=__('Featured')?></span>
                <?endif?>
                <a title="<?=HTML::chars($p->title)?>" href="<?=Route::url('product', array('controller'=>'product','category'=>$p->category->seoname,'seotitle'=>$p->seotitle))?>"> <?=$p->title; ?></a>
            </h4>
            <p><?=Text::limit_chars(Text::removebbcode($p->description),255,NULL,TRUE);?></p>
        </div>
    </li>
    <?endforeach?>
</ul>
<?endif?>