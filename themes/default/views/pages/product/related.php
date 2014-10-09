<?php defined('SYSPATH') or die('No direct script access.');?>

<?if(count($products)):?>
<h3><?=__('Related products')?></h3>
<ul class="media-list">
    <?foreach($products as $p ):?>
    <li class="media">
        <?if($p->get_first_image() !== NULL):?>
        <?$images_base = (core::config('image.aws_s3_active')) ? ((Core::is_HTTPS()) ? 'https://' : 'http://').core::config('image.aws_s3_domain') : URL::base()?>
        <a class="pull-left" title="<?=HTML::chars($p->title)?>" href="<?=Route::url('product', array('controller'=>'product','category'=>$p->category->seoname,'seotitle'=>$p->seotitle))?>">
            <img class="media-object" width="64" height="64" src="<?=$images_base.$p->get_first_image()?>" alt="<?=HTML::chars($p->title)?>">
        </a>
        <?endif?>
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