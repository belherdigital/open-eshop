<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=__('Social Authentication Settings')?></h1>
</div>
    <?if (Theme::get('premium')!=1):?>
        <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
            <?=__('Social authentication is only available with premium themes!').'<br/>'.__('Upgrade your Open eShop site to activate this feature.')?>
            <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
        </p>
    <?endif?>

    <div class="well">
    <?= FORM::open(Route::url('oc-panel',array('controller'=>'social', 'action'=>'index')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
        <fieldset>
            <div class="form-group">
            <?= FORM::label('debug_mode', __('Debug Mode'), array('class'=>'col-md-3 control-label', 'for'=>'debug_mode'))?>
                <div class="col-md-5">
                    <?=FORM::select('debug_mode', array(FALSE=>"FALSE",TRUE=>"TRUE"), $config['debug_mode'], array('class'=>'form-control'));?>
                </div>
            </div>
            <hr>
            <?foreach ($config['providers'] as $api => $options):?>
                <div class="form-group">
                <?= FORM::label($api, $api, array('class'=>'col-md-3 control-label', 'for'=>$api))?>
                    <div class="col-md-5">
                        <?=FORM::select($api, array(FALSE=>"FALSE",TRUE=>"TRUE"), $options['enabled'], array('class'=>'form-control'));?>
                    </div>
                </div>
                <?if(isset($options['keys']['id'])):?>
                    <div class="form-group">
                    <?= FORM::label($api.'_id_label', __('Id'), array('class'=>'col-md-3 control-label', 'for'=>$api))?>
                        <div class="col-md-5">
                            <?=FORM::input($api.'_id', $options['keys']['id']);?>
                        </div>
                    </div>
                <?endif?>
                <?if(isset($options['keys']['key'])):?>
                    <div class="form-group">
                    <?= FORM::label($api.'_key_label', __('Key'), array('class'=>'col-md-3 control-label', 'for'=>$api))?>
                        <div class="col-md-5">
                            <?=FORM::input($api.'_key', $options['keys']['key']);?>
                        </div>
                    </div>
                <?endif?>
                <?if(isset($options['keys']['secret'])):?>
                    <div class="form-group">
                    <?= FORM::label($api.'_secret_label', __('secret'), array('class'=>'col-md-3 control-label', 'for'=>$api))?>
                        <div class="col-md-5">
                            <?=FORM::input($api.'_secret', $options['keys']['secret']);?>
                        </div>
                    </div>
                <?endif?>
                <hr>
            <?endforeach?>
            <div class="form-actions">
                <?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'index'))))?>
            </div>
        </fieldset>
    <?FORM::close()?>
    </div>
