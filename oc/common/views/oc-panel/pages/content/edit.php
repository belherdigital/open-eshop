<?php defined('SYSPATH') or die('No direct script access.');?>

<?if($cont->type != 'email' AND $cont->status == 0):?>
    <ul class="list-inline pull-right">
        <li>
            <a class="btn btn-primary" target="_blank" href="<?=Route::url(($cont->type == 'help') ? 'faq' : 'page', array('seotitle'=>$cont->seotitle))?>" title="<?=__('Preview')?>">
                <i class="glyphicon glyphicon-eye-open"></i>
                <?=__('Preview')?>
            </a>
        </li>
    </ul>
<?endif?>

<h1 class="page-header page-title">
    <?=__('Edit')?> <?=Controller_Panel_Content::translate_type($cont->type)?>
</h1>

<hr>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content)), array('enctype'=>'multipart/form-data'))?>
            <fieldset>
                <div class="form-group">
                    <?=FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
                    <?=FORM::input('title', $cont->title, array('placeholder' => __('title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
                </div>
                <div class="form-group">
                    <?=FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
                    <?=FORM::select('locale', $locale, $cont->locale,array('placeholder' => __('locale'), 'class' => 'form-control', 'id' => 'locale', 'required'))?>
                </div>
                <div class="form-group">
                    <?=FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description'))?>
                    <?=FORM::textarea('description', $cont->description, array('id' => 'description','class' => 'form-control', 'data-editor'=>'html'))?>
                </div>
            
                <?if($cont->type == 'email'):?>
                    <div class="form-group">
                        <?=FORM::label('from_email', __('From email'), array('class'=>'control-label', 'for'=>'from_email'))?>
                        <?=FORM::input('from_email', $cont->from_email, array('placeholder' => __('from_email'), 'class' => 'form-control', 'id' => 'from_email'))?>
                    </div>
                <?endif?>
            
                <?if($cont->type != 'email'):?>
                    <div class="form-group">
                        <?=FORM::label('seotitle', __('Seotitle'), array('class'=>'control-label', 'for'=>'seotitle'))?>
                        <?=FORM::input('seotitle', $cont->seotitle, array('placeholder' => __('seotitle'), 'class' => 'form-control', 'id' => 'seotitle'))?>
                    </div>
                <?endif?>
            
                <div class="form-group">
                    <div class="checkbox check-success">
                        <input type="checkbox" name="status" id="status" <?=($cont->status == TRUE)?'checked':''?>>
                        <label for="status"><?=__('Active')?></label>
                    </div>
                </div>
                
                <hr>
                <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'content','action'=>'edit','id'=>$cont->id_content))))?>
            </fieldset>
        <?= FORM::close()?>
    </div>
</div>
