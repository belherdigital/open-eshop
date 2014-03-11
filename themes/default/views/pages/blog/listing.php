<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=Core::config('general.site_name')?> <?=__('Blog')?></h1>
    <div class="btn-group pull-right">
        <a href="#" id="list" class="btn btn-default btn-sm <?=(core::cookie('list/grid')==1)?'active':''?>">
            <span class="glyphicon glyphicon-th-list"></span><?=__('List')?>
        </a> 
        <a href="#" id="grid" class="btn btn-default btn-sm <?=(core::cookie('list/grid')==0)?'active':''?>">
            <span class="glyphicon glyphicon-th"></span><?=__('Grid')?>
        </a>
        <button type="button" id="sort" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-list-alt"></span><?=__('Sort')?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="?sort=title-asc"><?=__('Name (A-Z)')?></a></li>
            <li><a href="?sort=title-desc"><?=__('Name (Z-A)')?></a></li>
            <li><a href="?sort=price-asc"><?=__('Price (Low)')?></a></li>
            <li><a href="?sort=price-desc"><?=__('Price (High)')?></a></li>
            <li><a href="?sort=featured"><?=__('Featured')?></a></li>
            <li><a href="?sort=published-asc"><?=__('Newest')?></a></li>
            <li><a href="?sort=published-desc"><?=__('Oldest')?></a></li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>

<?if(count($posts)):?>
    <div id="products" class="list-group">
        <?$i=1;
        foreach($posts as $post ):?>    
            <div class="item <?=(core::cookie('list/grid')==1)?'list-group-item':'grid-group-item'?> col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <div class="thumbnail">
                    <div class="caption">
                        <h2>
                            <a class="big-txt <?=(core::cookie('list/grid')==0)?'hide':''?>" title="<?= $post->title;?>" href="<?=Route::url('blog', array('seotitle'=>$post->seotitle))?>" >
                            <?=Text::limit_chars(Text::removebbcode($post->title), 255, NULL, TRUE)?>
                            </a>
                            <a class="small-txt <?=(core::cookie('list/grid')==1)?'hide':''?>" title="<?= $post->title;?>" href="<?=Route::url('blog', array('seotitle'=>$post->seotitle))?>" >
                            <?=Text::limit_chars(Text::removebbcode($post->title), 30, NULL, TRUE)?>
                            </a>
                        </h2>
                        <p class="big-txt <?=(core::cookie('list/grid')==0)?'hide':''?>"><?=Text::limit_chars(Text::removebbcode($post->description), 255, NULL, TRUE)?></p>
                        <p class="small-txt <?=(core::cookie('list/grid')==1)?'hide':''?>"><?=Text::limit_chars(Text::removebbcode($post->description), 30, NULL, TRUE)?></p>
                        <?if ($user !== NULL AND $user!=FALSE):?>
                            <?if ($user->id_role == 10):?>
                                <br />
                                <a href="<?=Route::url('oc-panel', array('controller'=>'blog','action'=>'update','id'=>$post->id_post))?>"><?=__("Edit");?></a> |
                                <a href="<?=Route::url('oc-panel', array('controller'=>'blog','action'=>'delete','id'=>$post->id_post))?>" 
                                    onclick="return confirm('<?=__('Delete?')?>');"><?=__("Delete");?></a>
                            <?endif?>
                        <?endif?>
                    </div>
                </div>
            </div>
                <?if($i%3==0):?><div class="clearfix"></div><?endif?>
        <?$i++?>
        <?endforeach?>
    </div>
    <?=$pagination?>
<?else:?>
<!-- Case when we dont have ads for specific category / location -->
    <div class="page-header">
       <h3><?=__('We do not have any blog post')?></h3>
    </div>
<?endif?>

