<?php defined('SYSPATH') or die('No direct script access.');?>

	
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Payments Configuration')?></h1>
            <p class=""><?=__('List of payment configuration values. Replace input fields with new desired values.')?></p>

		</div>


		<div class="well">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>

                <div class="control-group">
                    <?= FORM::label($forms['paypal_account']['key'], __('Paypal account'), array('class'=>'control-label', 'for'=>$forms['paypal_account']['key']))?>
                    <div class="controls">
                        <?= FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
                        'placeholder' => "some@email.com", 
                        'class' => 'tips', 
                        'id' => $forms['paypal_account']['key'],
                        'data-content'=> __("Paypal mail address"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>__("The paypal email address where the payments will be sent"), 
                        ))?> 
                        </div>
                </div>

				<div class="control-group">
					<?= FORM::label($forms['sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['sandbox']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['sandbox']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['sandbox']['value'], array(
						'placeholder' => "TRUE or FALSE", 
						'class' => 'tipsti', 
						'id' => $forms['sandbox']['key'],
						'data-content'=> '',
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>'', 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['paypal_currency']['key'], __('Paypal currency'), array('class'=>'control-label', 'for'=>$forms['paypal_currency']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['paypal_currency']['key'], $paypal_currency , array_search($forms['paypal_currency']['value'], $paypal_currency), array(
						'placeholder' => "USD", 
						'class' => 'tips', 
						'id' => $forms['paypal_currency']['key'], 
						'data-content'=> __("Currency"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Please be sure you are using a currency that paypal supports."),
						))?> 
					</div>
				</div>
				

				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
				</div>
			</fieldset>	
	</div><!--end span10-->
