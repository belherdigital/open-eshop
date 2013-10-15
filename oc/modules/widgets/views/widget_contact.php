<?php defined('SYSPATH') or die('No direct script access.');?>

<h3><?=$widget->text_title?></h3>
<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'user_contact', 'id'=>$widget->id_ad)), array('class'=>'form-horizontal ', 'enctype'=>'multipart/form-data'))?>
	<fieldset>
		<div class="">
			<?= FORM::label('name', __('Name'), array('class'=>'', 'for'=>'name'))?>
			<div class=" ">
				<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => 'span2', 'id' => 'name', 'required'))?>
			</div>
		</div>
		<div class="">
			
			<?= FORM::label('email', __('Email'), array('class'=>'', 'for'=>'email'))?>
			<div class=" ">
				<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class' => 'span2', 'id' => 'email', 'type'=>'email','required'))?>
			</div>
		</div>
		<div class="">
			
			<?= FORM::label('subject', __('Subject'), array('class'=>'', 'for'=>'subject'))?>
			<div class=" ">
				<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class' => 'span2', 'id' => 'subject'))?>
			</div>
		</div>
		<div class="">
			<?= FORM::label('message', __('Message'), array('class'=>'', 'for'=>'message'))?>
			<div class="">
				<?= FORM::textarea('message', "", array('class'=>'span2', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>	
				</div>
		</div>

		<!-- file to be sent-->
		<?if(core::config('advertisement.upload_file')):?>
		<div class="">
			<?= FORM::label('file', __('File'), array('class'=>'', 'for'=>'file'))?>
			<div class="">
				<!-- <input type="file" name="file" id="file" /> -->
				<?= FORM::file('file', array('placeholder' => __('File'), 'class' => 'input-xlarge', 'id' => 'file'))?>
			</div>
		</div>
		<?endif?>
		
		<?if (core::config('advertisement.captcha') != FALSE):?>
		<div class="">
			<div class="">
				<?=__('Captcha')?>*:<br />
				<?=captcha::image_tag('contact')?><br />
				<?= FORM::input('captcha', "", array('class' => 'span2', 'id' => 'captcha', 'required'))?>
			</div>
		</div>
		<?endif?>
			
			<div class="modal-footer">	
			<?= FORM::button('submit', __('Send Message'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'user_contact' , 'id'=>$widget->id_ad))))?>
		</div>
	</fieldset>
	<?= FORM::close()?>
