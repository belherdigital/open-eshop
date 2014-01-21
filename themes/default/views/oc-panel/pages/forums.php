<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Forums')?></h1>
    <p><?=__("Change the order of your forums. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).")?></p>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'forum','action'=>'create'))?>">
  <?=__('New forum')?></a>
</div>

<ol class='plholder col-md-8' id="ol_1" data-id="0">
<?function lili($item, $key,$forums){?>
    <li data-id="<?=$key?>" id="li_<?=$key?>"><i class="icon-move"></i> <?=$forums[$key]['name']?>
        
        <a data-text="<?=__('Are you sure you want to delete? We will move the siblings forums and ads to the parent of this forum.')?>" 
           data-id="li_<?=$key?>" 
           class="btn btn-xs btn-danger pull-right"  
           href="<?=Route::url('oc-panel', array('controller'=> 'forum', 'action'=>'delete','id'=>$key))?>">
                    <i class="glyphicon glyphicon-trash"></i>
        </a>

        <a class="btn btn-xs btn-primary pull-right" 
            href="<?=Route::url('oc-panel',array('controller'=>'forum','action'=>'update','id'=>$key))?>">
            <?=__('Edit')?>
        </a>

        <ol data-id="<?=$key?>" id="ol_<?=$key?>">
            <? if (is_array($item)) array_walk($item, 'lili', $forums);?>
        </ol><!--ol_<?=$key?>-->

    </li><!--li_<?=$key?>-->
<?}array_walk($order, 'lili',$forums);?>
</ol><!--ol_1-->

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'forum','action'=>'saveorder'))?>'></span>