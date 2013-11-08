<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header pull-left">
    <h1><?=__('Social Authentication Settings')?></h1>
    <hr>
    <div class="well">
    <?= FORM::open(Route::url('oc-panel',array('controller'=>'social', 'action'=>'index')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
        <fieldset>
            <div class="control-group">
            <?= FORM::label('debug_mode', __('Debug Mode'), array('class'=>'control-label', 'for'=>'debug_mode'))?>
                <div class="controls">
                    <?=FORM::select('debug_mode', array(FALSE=>"FALSE",TRUE=>"TRUE"), $config['debug_mode']);?>
                </div>
            </div>
            <hr>
            <?foreach ($config['providers'] as $api => $options):?>
                <div class="control-group">
                <?= FORM::label($api, $api, array('class'=>'control-label', 'for'=>$api))?>
                    <div class="controls">
                        <?=FORM::select($api, array(FALSE=>"FALSE",TRUE=>"TRUE"), $options['enabled']);?>
                    </div>
                </div>
                <?if(isset($options['keys']['id'])):?>
                    <div class="control-group">
                    <?= FORM::label($api.'_id_label', __('Id'), array('class'=>'control-label', 'for'=>$api))?>
                        <div class="controls">
                            <?=FORM::input($api.'_id', $options['keys']['id']);?>
                        </div>
                    </div>
                <?endif?>
                <?if(isset($options['keys']['key'])):?>
                    <div class="control-group">
                    <?= FORM::label($api.'_key_label', __('Key'), array('class'=>'control-label', 'for'=>$api))?>
                        <div class="controls">
                            <?=FORM::input($api.'_key', $options['keys']['key']);?>
                        </div>
                    </div>
                <?endif?>
                <?if(isset($options['keys']['secret'])):?>
                    <div class="control-group">
                    <?= FORM::label($api.'_secret_label', __('secret'), array('class'=>'control-label', 'for'=>$api))?>
                        <div class="controls">
                            <?=FORM::input($api.'_secret', $options['keys']['secret']);?>
                        </div>
                    </div>
                <?endif?>
                <hr>
            <?endforeach?>
            <div class="form-actions">
                <?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'index'))))?>
            </div>
        </fieldset>
    <?FORM::close()?>
    </div>
</div>