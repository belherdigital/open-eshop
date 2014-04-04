<?php defined('SYSPATH') or die('No direct script access.');?>

<h1><?=__('New Forum')?></h1>

<?= FORM::open(Route::url('oc-panel',array('controller'=>'forum','action'=>'create')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
	<fieldset>
	    <div class="form-group">
	        <?= FORM::label('name', __('Name'), array('class'=>'col-md-3 control-label', 'for'=>'name'))?>
	        <div class="col-md-5">
	            <?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => '', 'id' => 'name', 'required'))?>
	        </div>
	    </div>
	    <div class="form-group">
	        <?= FORM::label('id_forum_parent', __('Forum parent'), array('class'=>'col-md-3 control-label', 'for'=>'id_forum_parent'))?>
	        <div class="col-md-5">
	           	<select name="id_forum_parent" id="id_forum_parent" class="form-control" placeholder="<?=__('Forum parent')?>">
	            	<option value="0"></option>
					<?foreach($forum_parents as $id => $name):?>
						<option value="<?=$id?>"><?=$name?></option>
					<?endforeach?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
	        <?= FORM::label('description', __('Description'), array('class'=>'col-md-3 control-label', 'for'=>'description'))?>
	        <div class="col-md-7">
	            <?= FORM::textarea('description', __('Description'), array('class'=>'form-control','id' => 'description'))?>
	        </div>
	    </div>
	    <div class="form-group">
	        <?= FORM::label('seoname', __('Seoname'), array('class'=>'col-md-3 control-label', 'for'=>'seoname'))?>
	        <div class="col-md-5">
	            <?= FORM::input('seoname', '', array('placeholder' => __('Seoname'), 'class' => '', 'id' => 'seoname'))?>
	        </div>
	    </div>
	    <div class="form-actions">
	        <?= FORM::button('submit', __('Create'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'forum','action'=>'create'))))?>
	    </div>
	</fieldset>
<?= FORM::close()?>