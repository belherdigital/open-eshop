<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <?if($type == 'page'):?>
        <h1><?=__('Create').' '.__('Page')?></h1>
    <?elseif($type == 'email'):?>
        <h1><?=__('Create').' '.__('Email')?></h1>
    <?elseif($type == 'help'):?>
        <h1><?=__('Create').' '.__('FAQ')?></h1>
    <?endif?>
</div>

 <?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'create')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
<fieldset>
    <div class="form-group">
        <?= FORM::label('title', __('Title'), array('class'=>'col-md-3 control-label', 'for'=>'title'))?>
        <div class="col-md-5">
            <?= FORM::input('title', '', array('placeholder' => __('Title'), 'class' => '', 'id' => 'title', 'required'))?>
        </div>
    </div>
    <div class="form-group">
        <?= FORM::label('locale', __('Locale'), array('class'=>'col-md-3 control-label', 'for'=>'locale'))?>
        <div class="col-md-5">
            <?= FORM::select('locale', $locale, core::config('i18n.locale'),array('placeholder' => __('locale'), 'class' => 'form-control', 'id' => 'locale', 'required'))?>
        </div>
    </div>
    <div class="form-group">
        <?= FORM::label('description', __('Description'), array('class'=>'col-md-3 control-label', 'for'=>'description'))?>
        <div class="col-md-9">
            <?= FORM::textarea('description', __('Description'), array('class'=>'col-md-10 col-sm-10 col-md-12','id' => 'description','data-editor'=>($type != 'email')?'html':'bbcode'))?>
        </div>
    </div>
    
    <?if($_REQUEST['type'] == 'email'):?>
    <div class="form-group">
        <?= FORM::label('from_email', __('From email'), array('class'=>'col-md-3 control-label', 'for'=>'from_email'))?>
        <div class="col-md-5">
            <?= FORM::input('from_email', '', array('placeholder' => __('from_email'), 'class' => '', 'id' => 'from_email'))?>
        </div>
    </div>
    <?endif?>
    <div class="form-group">
        <div class="col-md-5">
            <?= FORM::hidden('type', $type, array('placeholder' => __('Type'), 'class' => '', 'id' => 'type'))?>
        </div>
    </div>
    <div class="form-group">
    
        <div class="checkbox">
            <label class="status" >
                <input type="checkbox" name="status" ><?=__('Status')?>
            </label>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::button('submit', __('Create'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'content','action'=>'create'))))?>
    </div>
</fieldset>
<?= FORM::close()?>
   

