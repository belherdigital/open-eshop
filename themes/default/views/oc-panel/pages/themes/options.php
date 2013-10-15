<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Theme Options')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?></h1>
    <p><?=__('Here are listed specific theme configuration values. Replace input fields with new desired values for the theme.')?></p>
    <?if(Core::config('appearance.theme_mobile')!=''):?>
            <p>
                <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
                <a class="btn btn-mini btn-warning" title="<?=__('Disable')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
                    <i class="icon-remove icon-white"></i>
                </a>
                <a class="btn btn-mini btn-primary" title="<?=__('Options')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
                <i class="icon-wrench icon-white"></i></a>
            </p>
        <?endif?>
</div>

<div class="well">
<form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post" class="form-horizontal"> 
    <fieldset>
        <?foreach ($options as $field => $attributes):?>
            <div class="control-group">
                <?=FORM::form_tag($field, $attributes, (isset($data[$field]))?$data[$field]:NULL)?>
            </div>
        <?endforeach?>
		<div class="form-actions">
			<?= FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn-small btn-primary'))?>
		</div>
	</fieldset>	
</form>
</div><!--end span10-->