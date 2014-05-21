<?php defined('SYSPATH') or die('No direct script access.');?>

<h1><?=__('Update Topic')?></h1>

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
	    
    	<div class="col-md-offset-3">
		    <div class="checkbox">
		        <label>
		          	<input type="checkbox" name="status" <?=($topic->status==Model_Post::STATUS_ACTIVE)?'checked="checked"':''?>>&nbsp;<?=__('Activate')?>
		        </label>
	      	</div>
      	</div>
      	
	    <div class="form-actions">
	        <?= FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'topic','action'=>'update', 'id'=>$topic->id_post))))?>
	    </div>
	</fieldset>
<?= FORM::close()?>