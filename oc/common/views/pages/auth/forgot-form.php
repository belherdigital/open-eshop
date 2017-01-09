<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal auth"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'forgot'))?>">         
    <?=Form::errors()?>
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('Email')?></label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
        </div>
    </div>

    <hr>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <ul class="list-inline">
                <li>
                    <button type="submit" class="btn btn-primary"><?=_e('Send')?></button>
                </li>
                <li>
                    <?=_e('Don’t Have an Account?')?>
                    <a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
                        <?=_e('Register')?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?=Form::CSRF('forgot')?>
</form>         