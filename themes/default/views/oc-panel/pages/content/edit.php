<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
    <?if($cont->type == 'page'):?>
        <h1><?=__('Edit').' '.__('Page')?></h1>
    <?elseif($cont->type == 'email'):?>
        <h1><?=__('Edit').' '.__('Email')?></h1>
    <?elseif($cont->type == 'help'):?>
        <h1><?=__('Edit').' '.__('FAQ')?></h1>
    <?endif?>
</div>

 <?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
<fieldset>
    <div class="form-group">
        <?= FORM::label('title', __('Title'), array('class'=>'col-md-3 control-label', 'for'=>'title'))?>
        <div class="col-md-5">
            <?= FORM::input('title', $cont->title, array('placeholder' => __('title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
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
            <?= FORM::textarea('description', $cont->description, array('class'=>'form-control col-md-10 col-sm-10 col-xs-12','id' => 'description','data-editor'=>($cont->type != 'email')?'html':'bbcode'))?>
        </div>
    </div>
    <div class="form-group">
    <?= FORM::label('status', __('Status'), array('class'=>'col-md-3 control-label', 'for'=>'status'))?>
        <div class="col-md-5">
            <label class="status">
                <input type="checkbox" value="<?=$cont->status?>" name="status" <?=($cont->status == TRUE)?'checked':''?> >
            </label>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::button('submit', __('Edit'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content))))?>
    </div>
</fieldset>
<?= FORM::close()?>
   

