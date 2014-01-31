<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=__("New Forum Topic")?></h1>
</div>

<div class="well">
	<?php if ($errors): ?>
    <div class="alert alert-warning">
	    <?=__('Some errors were encountered, please check the details you entered.')?>
	    <ul class="errors">
		    <?php foreach ($errors as $message): ?>
		        <li><?php echo $message ?></li>
		    <?php endforeach ?>
	    </ul>
    </div>
    <?php endif ?>       
	<?=FORM::open(Route::url('forum-new'), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
	<fieldset>

        <div class="form-group">
            <?= FORM::label('id_forum', __('Forum'), array('class'=>'col-md-2 control-label', 'for'=>'id_forum' ))?>
            <div class="col-md-6">
                <select name="id_forum" id="id_forum" class="form-control" REQUIRED>
                    <option><?=__('Select a forum')?></option>
                    <?foreach ($forums as $f):?>
                        <option value="<?=$f['id_forum']?>" <?=(core::request('id_forum')==$f['id_forum'])?'selected':''?>>
                            <?=$f['name']?></option>
                    <?endforeach?>
                </select>
            </div>
        </div>

		<div class="form-group">
			<?= FORM::label('title', __('Title'), array('class'=>'col-md-2 control-label', 'for'=>'title'))?>
			<div class="col-md-6 ">
				<?= FORM::input('title', core::post('title'), array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label('description', __('Description'), array('class'=>'col-md-2 control-label', 'for'=>'description'))?>
			<div class="col-md-6">
				<?= FORM::textarea('description', core::post('description'), array('placeholder' => __('Description'), 'class' => 'form-control', 'name'=>'description', 'id'=>'description', 'required'))?>	
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<?=__('Captcha')?>*:<br />
				<?=captcha::image_tag('new-forum')?><br />
				<?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
			</div>
		</div>
		<div class="clearfix"></div><br>
		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<?= FORM::button('submit', __('Publish new topic'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('forum-new')))?>
			</div>
		</div>
	</fieldset>
	<?= FORM::close()?>

</div><!--end span10-->