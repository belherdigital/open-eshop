<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		<div class="page-header">
			<h1><?=__('Edit Profile')?></h1>
		</div>

		<?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'well form-horizontal', 'enctype'=>'multipart/form-data'))?>
				<div class="control-group">
					<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<div class="controls">
						<?= FORM::input('name', $user->name, array('class'=>'input-xlarge', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', $user->email, array('class'=>'input-xlarge', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
					</div>
				</div>
				

				<div class="form-actions">
					<?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))))?>
				</div>
		<?= FORM::close()?>
	</div>
	<!--/span-->
	
	<div class="span10">
    	<div class="page-header">
    		<h1><?=__('Change password')?></h1>
    	</div>
    	
    	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">         
              <?=Form::errors()?>  
              
              <div class="control-group">
                <label class="control-label"><?=__('New password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password1" placeholder="<?=__('Password')?>">
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label"><?=__('Repeat password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password2" placeholder="<?=__('Password')?>">
                  <p class="help-block">
                  		<?=__('Type your password twice to change')?>
                  </p>
                </div>
              </div>
              
              <div class="form-actions">
              	<a href="<?=Route::url('oc-panel')?>" class="btn"><?=__('Cancel')?></a>
                <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
              </div>
              <?=Form::CSRF()?>
    	</form>
    </div><!--end span10-->

    <div class="span10">
      <div class="page-header">
        <h1><?=__('Profile picture')?></h1>
      </div>
      
      <form class="well form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">         
              <?=Form::errors()?>  
              
            <?= FORM::label('profile_img', __('Profile picture'), array('class'=>'control-label', 'for'=>'profile_img'))?>
            <div class="control-group">
              <input type="file" name="profile_image" id="profile_img" />
            </div>
            <div class="form-actions">
                <a href="<?=Route::url('oc-panel')?>" class="btn"><?=__('Cancel')?></a>
                <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
              </div>
      </form>
    </div><!--end span10-->
</div>
<!--/row-->