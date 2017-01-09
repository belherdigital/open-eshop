<?php defined('SYSPATH') or die('No direct script access.');?>


<h1 class="page-header page-title"><?=__('Update Topic')?></h1>
<hr>

<div class="panel panel-default">
    <div class="panel-body">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'topic','action'=>'update', 'id'=>$topic->id_post)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
            	<fieldset>
                <div class="form-group">
                    <?= FORM::label('title', __('Title'), array('class'=>'col-md-3 control-label', 'for'=>'title'))?>
                    <div class="col-md-5">
                        <?= FORM::input('title', $topic->title, array('placeholder' => __('Title'), 'class' => '', 'id' => 'title', 'required'))?>
                    </div>
                </div>
    
                <div class="form-group">
                    <?= FORM::label('forum_parents', __('Forums'), array('class'=>'col-md-3 control-label', 'for'=>'id_post_parent'))?>
                    <div class="col-md-5">
                        <select name="id_forum" id="id_forum" class="form-control" placeholder="<?=__('Forum parent')?>">
                            <?foreach($forum_parents as $id => $name):?>
                                <option value="<?=$id?>" <?=($topic->id_forum == $id)?'selected="selected"':NULL?>><?=$name?></option>
                            <?endforeach?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= FORM::label('description', __('Description'), array('class'=>'col-md-3 control-label', 'for'=>'description'))?>
                    <div class="col-md-7">
                        <?= FORM::textarea('description', $topic->description, array('class'=>'form-control','id' => 'description'))?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= FORM::label('seotitle', __('Seotitle'), array('class'=>'col-md-3 control-label', 'for'=>'seotitle'))?>
                    <div class="col-md-5">
                        <?= FORM::input('seotitle', $topic->seotitle, array('placeholder' => __('Seotitle'), 'class' => '', 'id' => 'seotitle'))?>
                    </div>
                </div>
    
                <div class="form-group">
                    <div class="col-md-offset-3 col-sm-9">
                        <div class="checkbox check-success">
                            <input type="checkbox" name="status" id="status" <?=($topic->status == TRUE)?'checked':''?>>
                            <label for="status"><?=__('Active')?></label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?= FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'topic','action'=>'update', 'id'=>$topic->id_post))))?>
                        
                        <a  class="btn btn-danger" 
                            data-toggle="confirmation"
                            title="<?=__('Are you sure you want to delete?')?>" 
                            data-text="<?=__('Are you sure you want to delete?')?>" 
                            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                            data-btnCancelLabel="<?=__('No way!')?>"
                            href="<?=Route::url('oc-panel',array('controller'=>'topic','action'=>'delete', 'id'=>$topic->id_post))?>">
                        <i class="glyphicon glyphicon-trash"></i> <?=__('Delete')?></a>

                    </div>
                </div>
            </fieldset>
        <?= FORM::close()?>
    </div>
</div>