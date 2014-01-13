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
				<div class="control-group">
                    <?= FORM::label('signature', __('Email Signature'), array('class'=>'control-label', 'for'=>'signature'))?>
                    <div class="controls">
                        <?= FORM::input('signature', $user->signature, array('class'=>'input-xlarge', 'id'=>'signature', 'type'=>'signature', 'maxlength'=>'245'  ,'placeholder'=>__('Email Signature')))?>
                    </div>
                </div>

            <button type="submit" class="btn btn-primary"><?=__('Update')?></button>    		
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
              
                <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
    	</form>
    </div><!--end span10-->

    <div class="span10">
      <div class="page-header">
        <h1><?=__('Profile picture')?></h1>
      </div>

      <div class="row">
        
            <img src="<?=$user->get_profile_image()?>" class="img-rounded" alt="<?=__('Profile Picture')?>" height='200px'>
    </div>
      
      <form class="well form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">         
              <?=Form::errors()?>  
              
            <?= FORM::label('profile_img', __('Profile picture'), array('class'=>'control-label', 'for'=>'profile_img'))?>
            <div class="control-group">
              <input type="file" name="profile_image" id="profile_img" />
            </div>
                <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
      </form>
    </div><!--end span10-->
</div>
<!--/row-->