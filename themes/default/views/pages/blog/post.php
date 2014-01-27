<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?= $post->title;?></h1>
</div>

<div class="well">
    <span class="label label-warning" ><?=$post->user->name?></span>
    <div class="pull-right">
        <span class="label label-info"><?=Date::format($post->created, core::config('general.date_format'))?></span>
    </div>    
</div>

<br/>

<div>
    <?=Text::bb2html($post->description,TRUE)?>
</div>  
<div class="clearfix"></div><br>
<div class="pull-right">
    <?if($previous->loaded()):?>
        <a class="btn btn-success" href="<?=Route::url('blog',  array('seotitle'=>$previous->seotitle))?>" title="<?=$previous->title?>">
        <i class="glyphicon glyphicon-backward"></i></a>
    <?endif?>
    <?if($next->loaded()):?>
        <a class="btn btn-success" href="<?=Route::url('blog',  array('seotitle'=>$next->seotitle))?>" title="<?=$next->title?>">
        <i class="glyphicon glyphicon-forward"></i></a>
    <?endif?>
</div>  
    
<?=$post->disqus()?>