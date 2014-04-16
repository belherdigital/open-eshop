<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
		<h1><?=__('Edit Profile')?></h1>
	</div>

	<?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'well form-horizontal', 'enctype'=>'multipart/form-data'))?>
		<div class="form-group">
			<?= FORM::label('name', __('Name'), array('class'=>'col-md-2 control-label', 'for'=>'name'))?>
			<div class="col-md-5">
				<?= FORM::input('name', $user->name, array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label('email', __('Email'), array('class'=>'col-md-2 control-label', 'for'=>'email'))?>
			<div class="col-md-5">
				<?= FORM::input('email', $user->email, array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
			</div>
		</div>
        <div class="form-group">
            <?= FORM::label('paypal_email', __('Paypal email'), array('class'=>'col-md-2 control-label', 'for'=>'paypal_email'))?>
            <div class="col-md-5">
                <?= FORM::input('paypal_email', $user->paypal_email, array('class'=>'form-control', 'id'=>'paypal_email', 'type'=>'paypal_email' ,'required','placeholder'=>__('Paypal email')))?>
            </div>
        </div>
		<div class="form-group">
            <?= FORM::label('signature', __('Email Signature'), array('class'=>'col-md-2 control-label', 'for'=>'signature'))?>
            <div class="col-md-5">
                <?= FORM::input('signature', $user->signature, array('class'=>'form-control', 'id'=>'signature', 'type'=>'signature', 'maxlength'=>'245'  ,'placeholder'=>__('Email Signature')))?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?=__('Update')?></button>    		
    <?= FORM::close()?>

	<div class="clearfix"></div>
	<div class="page-header">
		<h1><?=__('Change password')?></h1>
	</div>
	
	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">         
        <?=Form::errors()?>  
          
        <div class="form-group">
            <label class="col-md-2 control-label"><?=__('New password')?></label>
            <div class="col-md-5 docs-input-sizes">
            <input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
            </div>
        </div>
          
        <div class="form-group">
            <label class="col-md-2 control-label"><?=__('Repeat password')?></label>
            <div class="col-md-5 docs-input-sizes">
            <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
                <p class="help-block">
              		<?=__('Type your password twice to change')?>
                </p>
            </div>
        </div>
          
        <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
	</form>

    <div class="clearfix"></div>
    <div class="page-header">
        <h1><?=__('Profile picture')?></h1>
    </div>
    
    <img src="<?=$user->get_profile_image()?>" class="img-rounded ticket_image" alt="<?=__('Profile Picture')?>" width="120px" height="120px">
    <div class="clearfix"></div><br>
    <form class="well form-inline" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">         
        <?=Form::errors()?>  
              
        <?= FORM::label('profile_img', __('Profile picture'), array('class'=>'col-md-2 control-label', 'for'=>'profile_img'))?>
        <div class="form-group">
            <div class="">
                <input class="form-control" type="file" name="profile_image" id="profile_img" />
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
    </form>
