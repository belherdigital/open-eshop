<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=__('Edit Profile')?></h3>
            </div>
            <div class="panel-body">
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
                    <div class="form-group">
                        <?= FORM::label('name', __('Name'), array('class'=>'col-md-4 control-label', 'for'=>'name'))?>
                        <div class="col-md-8">
                            <?= FORM::input('name', $user->name, array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('email', __('Email'), array('class'=>'col-md-4 control-label', 'for'=>'email'))?>
                        <div class="col-md-8">
                            <?= FORM::input('email', $user->email, array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('paypal_email', __('Paypal email'), array('class'=>'col-md-4 control-label', 'for'=>'paypal_email'))?>
                        <div class="col-md-8">
                            <?= FORM::input('paypal_email', $user->paypal_email, array('class'=>'form-control', 'id'=>'paypal_email', 'type'=>'paypal_email','placeholder'=>__('Paypal email')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('signature', __('Email Signature'), array('class'=>'col-md-4 control-label', 'for'=>'signature'))?>
                        <div class="col-md-8">
                            <?= FORM::input('signature', $user->signature, array('class'=>'form-control', 'id'=>'signature', 'type'=>'signature', 'maxlength'=>'245'  ,'placeholder'=>__('Email Signature')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('description', __('Description'), array('class'=>'col-md-4 control-label', 'for'=>'description'))?>
                        <div class="col-md-8">
                            <?= FORM::input('description', $user->description, array('class'=>'form-control', 'id'=>'description', 'type'=>'description' ,'placeholder'=>__('Description')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="subscriber" value="1" <?=($user->subscriber)?'checked':NULL?> > <?=__('Subscribed to emails')?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <button type="submit" class="btn btn-primary"><?=__('Update')?></button> 
                        </div>
                    </div>
                <?= FORM::close()?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Billing Information')?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'billing'))?>">   
                    <?=Form::errors()?>  
                    <div class="form-group">
                        <?= FORM::label('VAT_number', __('VAT Number'), array('class'=>'col-md-4 control-label', 'for'=>'VAT_number'))?>
                        <div class="col-md-8">
                            <?= FORM::input('VAT_number', $user->VAT_number, array('class'=>'form-control', 'id'=>'VAT_number', 'type'=>'VAT_number', 'maxlength'=>'65'  ,'placeholder'=>__('VAT Number')))?>
                        </div>
                    </div>
                     <div class="form-group">
                        <?= FORM::label('country', __('Country'), array('class'=>'col-md-4 control-label', 'for'=>'email'))?>
                        <div class="col-md-8">
                            <select name="country" id="country" class="form-control">
                                <option></option>
                                <?foreach (euvat::$countries as $country_code => $country_name):?>
                                    <option value="<?=$country_code?>" <?=( $user->country==$country_code)?'selected':''?>><?=$country_name?></option>
                                <?endforeach?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('city', __('City'), array('class'=>'col-md-4 control-label', 'for'=>'city'))?>
                        <div class="col-md-8">
                            <?= FORM::input('city', $user->city, array('class'=>'form-control', 'id'=>'city', 'type'=>'city', 'maxlength'=>'65'  ,'placeholder'=>__('City')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('postal_code', __('Postal Code'), array('class'=>'col-md-4 control-label', 'for'=>'postal_code'))?>
                        <div class="col-md-8">
                            <?= FORM::input('postal_code', $user->postal_code, array('class'=>'form-control', 'id'=>'postal_code', 'type'=>'postal_code', 'maxlength'=>'20'  ,'placeholder'=>__('Postal Code')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('address', __('Address'), array('class'=>'col-md-4 control-label', 'for'=>'address'))?>
                        <div class="col-md-8">
                            <?= FORM::input('address', $user->address, array('class'=>'form-control', 'id'=>'address', 'type'=>'address', 'maxlength'=>'150'  ,'placeholder'=>__('Address')))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                        </div>
                    </div>
                    <input type="hidden" name="order_id" value="<?=core::request('order_id')?>">
                </form>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Change password')?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">         
                    <?=Form::errors()?>  
                    <div class="form-group">
                        <label class="col-md-4 control-label"><?=__('New password')?></label>
                        <div class="col-md-8 docs-input-sizes">
                        <input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label"><?=__('Repeat password')?></label>
                        <div class="col-md-8 docs-input-sizes">
                        <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
                            <p class="help-block">
                                  <?=__('Type your password twice to change')?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?if( Core::config('general.google_authenticator')==TRUE):?>
        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=__('2 Step Authentication')?></h3>
                <p>
                <?=__('2 step authentication provided by Google Authenticator.')?> 
                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Android</a>, 
                <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8">iOS</a>
                </p>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <?if ($user->google_authenticator!=''):?>
                            <img src="<?=$user->google_authenticator_qr()?>">
                            <p>
                            <?=__('Google Authenticator Code')?>: <?=$user->google_authenticator?>
                            </p>
                            <a class="btn btn-warning" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'disable'))?>">
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=__('Disable')?>
                            </a>
                        <?else:?>
                            <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'enable'))?>">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=__('Enable')?>
                            </a>
                        <?endif?>
                    </div>
                </div>
            </div>
        </div>
        <?endif?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Profile picture')?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">         
                    <?=Form::errors()?>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <img src="<?=$user->get_profile_image()?>" class="img-rounded ticket_image" alt="<?=__('Profile Picture')?>" width="120" height="120">
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('profile_img', __('Profile picture'), array('class'=>'col-md-4 control-label', 'for'=>'profile_img'))?>
                        <div class="col-md-8">
                            <input class="form-control" type="file" name="profile_image" id="profile_img" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                            <?if ($user->has_image):?>
                                <button type="submit"
                                        class="btn btn-danger index-delete index-delete-inline"
                                        onclick="return confirm('<?=__('Delete photo?')?>');" 
                                        type="submit" 
                                        name="photo_delete"
                                        value="1" 
                                        title="<?=__('Delete photo')?>">
                                        <?=__('Delete photo')?>
                                </button>
                            <?endif?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>