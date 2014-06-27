<?php defined('SYSPATH') or die('No direct script access.');?>

	
		<?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Affiliates Configuration')?></h1>
            <p class=""><?=__('List of affiliates configuration values. Replace input fields with new desired values.')?></p>
            <?if (Theme::get('premium')!=1):?>
                <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
                    <?=__('Affiliate system is only available with premium themes!').'<br/>'.__('Upgrade your Open eShop site to activate this feature.')?>
                    <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
                </p>
            <?endif?>
		</div>

		<div class="well">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'affiliates')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>

                <div class="form-group">
                    <?= FORM::label($forms['active']['key'], __('Active'), array('class'=>'col-md-3 control-label', 'for'=>$forms['active']['key']))?>
                    <div class="col-md-5">
                        <div class="onoffswitch">
                            <?= Form::checkbox($forms['active']['key'], 1, (bool) $forms['active']['value'], array(
                            'placeholder' => "TRUE or FALSE", 
                            'class' => 'onoffswitch-checkbox', 
							'id' => $forms['active']['key'], 
							'data-content'=> '',
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>'',                     
                            ))?>
                            <?= FORM::label($forms['active']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['active']['key']))?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?= FORM::label($forms['cookie']['key'], __('Cookie Days'), array('class'=>'col-md-3 control-label', 'for'=>$forms['cookie']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['cookie']['key'], $forms['cookie']['value'], array(
                        'placeholder' => "90", 
                        'class' => 'tips form-control', 
                        'id' => $forms['cookie']['key'],
                        'data-content'=> __("How many days lasts the tracking cookie"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>__("How many days lasts the tracking cookie"), 
                        ))?> 
                        </div>
                </div>

                <div class="form-group">
                    <?= FORM::label($forms['payment_days']['key'], __('Payment Days'), array('class'=>'col-md-3 control-label', 'for'=>$forms['payment_days']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['payment_days']['key'], $forms['payment_days']['value'], array(
                        'placeholder' => "30", 
                        'class' => 'tips form-control', 
                        'id' => $forms['payment_days']['key'],
                        'data-content'=> __("How many days until a commission is eligible for payment"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>__("Days to get paid"), 
                        ))?> 
                        </div>
                </div>

                <div class="form-group">
                    <?= FORM::label($forms['payment_min']['key'], __('Minimal Payment'), array('class'=>'col-md-3 control-label', 'for'=>$forms['payment_min']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['payment_min']['key'], $forms['payment_min']['value'], array(
                        'placeholder' => "30", 
                        'class' => 'tips form-control', 
                        'id' => $forms['payment_min']['key'],
                        'data-content'=> __("Minimum amount to get paid the commissions"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>__("Amount to get paid"), 
                        ))?> 
                        </div>
                </div>

				
                <div class="form-group">
                <?= FORM::label($forms['tos']['key'], __('TOS'), array('class'=>'col-md-3 control-label', 'for'=>$forms['tos']['key']))?>
                <div class="col-md-5">
                    <?= FORM::select($forms['tos']['key'], $pages, $forms['tos']['value'], array( 
                    'class' => 'tips form-control', 
                    'id' => $forms['tos']['key'], 
                    'data-content'=> __('Terms of service page for the affiliate program'),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__('Terms of Service'),
                    ))?> 
                </div>
                </div>
               
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'affiliates'))))?>
				</div>
			</fieldset>	
	</div><!--end col-md-10-->
