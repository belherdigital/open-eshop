<?php defined('SYSPATH') or die('No direct script access.');?>
<h1 class="page-header page-title"><?=__('Create Blog Post')?></h1>
<hr>
<?//var_dump($form)?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'blog','action'=>'create')), array('enctype'=>'multipart/form-data'))?>
        <fieldset>
            <?= FORM::hidden($form['id_post']['name'], $form['id_post']['value'])?>
            <?= FORM::hidden($form['id_user']['name'], $form['id_user']['value'])?>
            <?= FORM::hidden($form['seotitle']['name'], $form['seotitle']['value'])?>
            <div class="form-group">
                <?=FORM::label($form['title']['id'], __('Title'), array('class'=>'control-label', 'for'=>$form['title']['id']))?>
                <?=FORM::input($form['title']['name'], '', array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => $form['title']['id'], 'required'))?>
            </div>
            <div class="form-group">
                <?=FORM::label($form['description']['id'], __('Description'), array('class'=>'control-label', 'for'=>$form['description']['id']))?>
                <?=FORM::textarea($form['description']['name'], '', array('class'=>'form-control','id' => $form['description']['id'],'data-editor'=>'html', 'placeholder'=>__('Description')))?>
            </div>
            <div class="form-group">
                <div class="checkbox check-success">
                    <?=FORM::checkbox($form['status']['name'], 1, (bool) $form['status']['value'], ['id' => 'status'])?>
                    <label for="status"><?=__('Active')?></label>
                </div>
            </div>

            <hr>
            <?=FORM::button('submit', __('Create'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'blog','action'=>'create'))))?>
        </fieldset>
        <?= FORM::close()?>
    </div>
</div>
