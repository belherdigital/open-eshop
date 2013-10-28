<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Pages')?></h1>
    
    <a class="btn btn-primary pull-right" href="http://open-classifieds.com/documentation/translate/"><?=__('New Page')?></a>

</div>
<?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'content')), array('method'=>'GET','class'=>'form-horizontal', 'id'=>'locale_form','enctype'=>'multipart/form-data'))?>
    <div class="control-group">
        <?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
        <div class="controls">
            <?= FORM::select('locale_select', $locale_list, $_REQUEST['locale_select'] )?> 
        </div>
        <div class="controls">
            <?= FORM::hidden('type', $type )?> 
        </div>
    </div>
<?= FORM::close()?>
<table class="table table-bordered">
    <tr>
        <th><?=__('Title')?></th>
        <th><?=__('locale')?></th>
        <th><?=__('created')?></th>
        <th><?=__('status')?></th>
        <th></th>
    </tr>
<?foreach ($contents as $content):?>
 
    <tr>
        <td><?=$content->title?></td>
        <td><?=$content->locale?></td>
        <td><?=$content->created?></td>
        <td><?=$content->status?></td>
        <td width="5%">
            
            <a class="btn btn-primary" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$content))?>" 
                rel"tooltip" title="<?=__('Edit')?>">
                <i class="icon-edit icon-white"></i>
            </a>
            <a class="btn btn-danger" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$content))?>" 
                rel"tooltip" title="<?=__('Delete')?>">
                <i class="icon-trash icon-white"></i>
            </a>

        </td>
    </tr>
    
<?endforeach?>
</table>