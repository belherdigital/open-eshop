<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-primary pull-right ajax-load" 
    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'create')).'?type='.$type ?>" 
    rel="tooltip" title="<?=__('New')?>"><i class="fa fa-plus-circle"></i>&nbsp; <?=__('New')?>
</a>

<h1  class="page-header page-title"><?=Controller_Panel_Content::translate_type($type)?></h1>
<hr>

<?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'list')), array('method'=>'GET','class'=>'form-horizontal', 'id'=>'locale_form','enctype'=>'multipart/form-data'))?>
    <div class="form-group">

        <div class="col-sm-4">
            <?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
            <?= FORM::select('locale_select', $locale_list, $locale )?> 
        </div>
        <div class="col-sm-4">
            <?= FORM::hidden('type', $type )?> 
        </div>
    </div>
<?= FORM::close()?>

<?if (count($contents)>0):?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ol class='plholder' id="ol_1" data-id="1">
                    <?foreach($contents as $content):?>
                        <li data-id="<?=$content->id_content?>" id="<?=$content->id_content?>">
                            <div class="drag-item">
                                <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                <div class="drag-name">
                                    <?=$content->title?>
                                    <?if ($content->status==1) : ?>
                                        <span class="label label-info "><?=__('Active')?></span>
                                    <?endif?>
                                </div>
                                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$content->id_content))?>">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$content->id_content))?>"
                                    class="drag-action index-delete" 
                                    title="<?=__('Are you sure you want to delete?')?>" 
                                    data-id="<?=$content->id_content?>" 
                                    data-text="<?=__('All data contained will be deleted.')?>" 
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </div>
                        </li>
                    <?endforeach?>
                </ol><!--ol_1-->
                <span id='ajax_result' data-url='<?=Route::url('oc-panel', array('controller'=>'content','action'=>'saveorder'))?>?to_locale=<?=$locale?>&type=<?=$type?>'></span>
            </div>
        </div>
    </div>
</div>

<hr>
<?else:?>
    <a class="btn btn-warning btn-lg pull-right" href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'copy'))?>?to_locale=<?=$locale?>&type=<?=$type?>"  >
        <?=sprintf(__('Create all new %s from original'),$type)?>
    </a>
<?endif?>