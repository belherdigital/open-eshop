<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (!$widget->loaded):?>
<li class="well col-md-2">
    <b><?=$widget->title?></b>
    <p><?=$widget->description?></p>
    <button  class="btn btn-primary btn-mini" data-toggle="modal" data-target="#modal_<?=$widget->id_name()?>" type="button">
            <?=__('Create')?>
    </button>
</li> 
<?else:?>
    <li class="liholder" id="<?=$widget->id_name()?>"><i class="glyphicon glyphicon-move"></i>  <?=$widget->title()?> <span class="muted"><?=$widget->title?></span>
        <button class="btn btn-primary btn-mini pull-right" data-toggle="modal" data-target="#modal_<?=$widget->id_name()?>" type="button"><?=__('Edit')?></button>
    </li>
<?endif?>

<div id="modal_<?=$widget->id_name()?>" class="modal hide fade" role="dialog" aria-labelledby="modal_<?=$widget->id_name()?>" aria-hidden="true">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=$widget->title?></h3>
    <p><?=$widget->description?></p>
  </div>

  <div class="modal-body">
    <form class="form-horizontal" id="form_widget_<?=$widget->id_name()?>" name="form_widget_<?=$widget->id_name()?>" method="post" action="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'save'))?>" >
        
        <div class="control-group">
                <label class="control-label" for="placeholder_form"><?=__('Where do you want the widget displayed?')?></label>
                <div class="controls">
                    <?=FORM::select('placeholder', array_combine(widgets::get_placeholders(TRUE),widgets::get_placeholders(TRUE)),$widget->placeholder)?>
                </div>
        </div>

		<?foreach ($tags as $tag):?>
            <div class="control-group">
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
        <a onclick="return confirm('<?=__('Sure you want to delete the widget? You can move it to the inactive placeholder')?>');"
            href="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'remove','id'=>$widget->widget_name))?>" class="btn btn-danger pull-left">
            <i class="glyphicon glyphicon-trash?v=2.1.2"></i></a>
    <?endif?>

    <button onclick="form_widget_<?=$widget->id_name()?>.submit();" class="btn btn-primary"><?=__('Save changes')?></button>

  </div>

</div>