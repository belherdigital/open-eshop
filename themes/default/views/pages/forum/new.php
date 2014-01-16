<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=__("New Forum Topic")?></h1>
</div>

<div class="well">
	<?php if ($errors): ?>
    <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
    <ul class="errors">
    <?php foreach ($errors as $message): ?>
        <li><?php echo $message ?></li>
    <?php endforeach ?>
    </ul>
    <?php endif ?>       
	<?=FORM::open(Route::url('forum-new'), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
	<fieldset>

        <div class="control-group">
            <?= FORM::label('id_forum', __('Forum'), array('class'=>'control-label', 'for'=>'id_forum' ))?>
            <div class="controls">
                <select name="id_forum" id="id_forum" class="input-xlarge" REQUIRED>
                    <option><?=__('Select a forum')?></option>
                    <?foreach ($forums as $f):?>
                        <option value="<?=$f['id_forum']?>" <?=(core::request('id_forum')==$f['id_forum'])?'selected':''?>>
                            <?=$f['name']?></option>
                    <?endforeach?>
                </select>
            </div>
        </div>

		<div class="control-group">
			<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
			<div class="controls ">
				<?= FORM::input('title', core::post('title'), array('placeholder' => __('Title'), 'class' => 'span6', 'id' => 'title', 'required'))?>
			</div>
		</div>
		<div class="control-group">
			<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description'))?>
			<div class="controls">
				<?= FORM::textarea('description', core::post('description'), array('placeholder' => __('Description'), 'class' => 'span6', 'name'=>'description', 'id'=>'description', 'required'))?>	
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<?=__('Captcha')?>*:<br />
				<?=captcha::image_tag('new-forum')?><br />
				<?= FORM::input('captcha', "", array('class' => 'input-xlarge', 'id' => 'captcha', 'required'))?>
			</div>
		</div>
	
		<div class="control-group">
			<div class="controls">
				<?= FORM::button('submit', __('Publish new topic'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('forum-new')))?>
			</div>
			<br class="clear">
		</div>
	</fieldset>
	<?= FORM::close()?>

</div><!--end span10-->