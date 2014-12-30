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
                <?= FORM::input('paypal_email', $user->paypal_email, array('class'=>'form-control', 'id'=>'paypal_email', 'type'=>'paypal_email','placeholder'=>__('Paypal email')))?>
            </div>
        </div>
		<div class="form-group">
            <?= FORM::label('signature', __('Email Signature'), array('class'=>'col-md-2 control-label', 'for'=>'signature'))?>
            <div class="col-md-5">
                <?= FORM::input('signature', $user->signature, array('class'=>'form-control', 'id'=>'signature', 'type'=>'signature', 'maxlength'=>'245'  ,'placeholder'=>__('Email Signature')))?>
            </div>
        </div>
        <div class="form-group">
                <?= FORM::label('description', __('Description'), array('class'=>'col-md-2 control-label', 'for'=>'description'))?>
                <div class="col-md-5">
                    <?= FORM::input('description', $user->description, array('class'=>'form-control', 'id'=>'description', 'type'=>'description' ,'placeholder'=>__('Description')))?>
                </div>
            </div>
         <div class="col-md-offset-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="subscriber" value="1" <?=($user->subscriber)?'checked':NULL?> > <?=__('Subscribed to emails')?>
                </label>
            </div>
            </div>

        <button type="submit" class="btn btn-primary"><?=__('Update')?></button>    		
    <?= FORM::close()?>

    <div class="clearfix" id="billing"></div>
    <div class="page-header">
        <h1><?=__('Billing Information')?></h1>
    </div>
    <form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'billing'))?>">   
        <?=Form::errors()?>  
        <div class="form-group">
            <?= FORM::label('VAT_number', __('VAT Number'), array('class'=>'col-md-2 control-label', 'for'=>'VAT_number'))?>
            <div class="col-md-5">
                <?= FORM::input('VAT_number', $user->VAT_number, array('class'=>'form-control', 'id'=>'VAT_number', 'type'=>'VAT_number', 'maxlength'=>'65'  ,'placeholder'=>__('VAT Number')))?>
            </div>
        </div>

         <div class="form-group">
            <?= FORM::label('country', __('Country'), array('class'=>'col-md-2 control-label', 'for'=>'email'))?>
            <div class="col-md-5">
                <select name="country" id="country" class="form-control">
                    <option></option>
                    <?foreach (euvat::$countries as $country_code => $country_name):?>
                        <option value="<?=$country_code?>" <?=( $user->country==$country_code)?'selected':''?>><?=$country_name?></option>
                    <?endforeach?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label('city', __('City'), array('class'=>'col-md-2 control-label', 'for'=>'city'))?>
            <div class="col-md-5">
                <?= FORM::input('city', $user->city, array('class'=>'form-control', 'id'=>'city', 'type'=>'city', 'maxlength'=>'65'  ,'placeholder'=>__('City')))?>
            </div>
        </div>
        <div class="form-group">
            <?= FORM::label('postal_code', __('Postal Code'), array('class'=>'col-md-2 control-label', 'for'=>'postal_code'))?>
            <div class="col-md-5">
                <?= FORM::input('postal_code', $user->postal_code, array('class'=>'form-control', 'id'=>'postal_code', 'type'=>'postal_code', 'maxlength'=>'20'  ,'placeholder'=>__('Postal Code')))?>
            </div>
        </div>
        <div class="form-group">
            <?= FORM::label('address', __('Address'), array('class'=>'col-md-2 control-label', 'for'=>'address'))?>
            <div class="col-md-5">
                <?= FORM::input('address', $user->address, array('class'=>'form-control', 'id'=>'address', 'type'=>'address', 'maxlength'=>'150'  ,'placeholder'=>__('Address')))?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
            </div>
        </div>
        <input type="hidden" name="order_id" value="<?=core::request('order_id')?>">
    </form>
                

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
    
    <img src="<?=$user->get_profile_image()?>" class="img-rounded ticket_image" alt="<?=__('Profile Picture')?>" width="120" height="120">
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
