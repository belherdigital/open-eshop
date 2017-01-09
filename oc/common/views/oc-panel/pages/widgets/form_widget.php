<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (!$widget->loaded):?>
    <div class="col-md-4 widget-boxes">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b><?=$widget->title?></b>
            </div>
            <div class="panel-body">
                <p><?=$widget->description?></p>
                <button  class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal_<?=$widget->id_name()?>" type="button">
                        <?=__('Create')?>
                </button>
            </div>
        </div>
    </div> 
<?else:?>
    <li class="liholder" id="<?=$widget->id_name()?>"><i class="glyphicon glyphicon-move"></i>  <?=$widget->title()?> <span class="muted"><?=$widget->title?></span>
        <button class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#modal_<?=$widget->id_name()?>" type="button"><?=__('Edit')?></button>
    </li>
<?endif?>

<div id="modal_<?=$widget->id_name()?>" class="modal fade" role="dialog" aria-labelledby="modal_<?=$widget->id_name()?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=$widget->title?></h4>
            </div>
            <div class="modal-body">
                <h5><?=$widget->description?></h5>
                <form id="form_widget_<?=$widget->id_name()?>" name="form_widget_<?=$widget->id_name()?>" method="post" action="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'save'))?>" >
                    
                    <div class="form-group">
                        <label class="control-label" for="placeholder_form"><?=__('Where do you want the widget displayed?')?></label>
                        <?=FORM::select('placeholder', array_combine(widgets::get_placeholders(TRUE),widgets::get_placeholders(TRUE)),$widget->placeholder)?>
                    </div>

            		<?foreach ($tags as $tag):?>
                        <div class="form-group">
                            <?=$tag?>
                        </div>
            		<?endforeach?>

            		<?if ($widget->loaded):?>
                        <input type="hidden" name="widget_name" value="<?=$widget->widget_name?>" >
            		<?endif?>
                    <input type="hidden" name="widget_class" value="<?=get_class($widget)?>" >
            	</form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true" ><?=__('Close')?></button>
                
                <?if ($widget->loaded):?>
                    <a
                        href="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'remove','id'=>$widget->widget_name))?>" 
                        class="btn btn-danger pull-left" 
                        title="<?=__('Sure you want to delete the widget?')?>" 
                        data-toggle="confirmation" 
                        data-href="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'remove','id'=>$widget->widget_name))?>" 
                        data-title="<?=__('You can move it to the inactive placeholder')?>" 
                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                        data-btnCancelLabel="<?=__('No way!')?>">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>
                <?endif?>

                <button onclick="form_widget_<?=$widget->id_name()?>.submit();" class="btn btn-primary"><?=__('Save changes')?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->