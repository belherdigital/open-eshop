<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'forum','action'=>'create'))?>" title="<?=__('New forum')?>">
    <i class="fa fa-plus-circle"></i>&nbsp; <?=__('New')?>
</a>

<h1 class="page-header page-title"><?=__('Forums')?></h1>
<hr>
<p><?=__("Change the order of your forums. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).")?> <a href="https://docs.yclas.com/add-forums-section/" target="_blank"><?=__('Read more')?></a></p>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ol class='plholder' id="ol_1" data-id="0">
                    <?function lili($item, $key,$forums){?>
                        <li data-id="<?=$key?>" id="li_<?=$key?>">
                            <div class="drag-item">
                                <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                <div class="drag-name">
                                    <?=$forums[$key]['name']?>
                                </div>
                                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                    href="<?=Route::url('oc-panel',array('controller'=>'forum','action'=>'update','id'=>$key))?>">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=> 'forum', 'action'=>'delete','id'=>$key))?>"
                                    class="drag-action index-delete" 
                                    title="<?=__('Are you sure you want to delete?')?>" 
                                    data-id="li_<?=$key?>" 
                                    data-text="<?=__('We will remove all the forum posts and answers.')?>"
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </div>
                        
                            <ol data-id="<?=$key?>" id="ol_<?=$key?>">
                                <? if (is_array($item)) array_walk($item, 'lili', $forums);?>
                            </ol><!--ol_<?=$key?>-->
                        
                        </li><!--li_<?=$key?>-->
                    <?}array_walk($order, 'lili',$forums);?>
                </ol><!--ol_1-->
                
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'forum','action'=>'saveorder'))?>'></span>
            </div>
        </div>
    </div>
</div>