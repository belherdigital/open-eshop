<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('New Order')?></h1>
</div>

<div class=" well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'order','action'=>'create')), array('class'=>'form-horizontal product_form'))?>


        <div class="control-group ">
    <label for="formorm_paymethod" class="control-label"><?=__('Name')?></label>  <div class="controls">
        <input type="text" id="formorm_paymethod" name="name" value="" >                            </div>
</div>

        <div class="control-group ">
    <label for="formorm_paymethod" class="control-label"><?=__('Email')?></label>  <div class="controls">
        <input type="text" id="formorm_paymethod" name="email" value="" >                            </div>
</div>

<div class="control-group">
                <?= FORM::label('paymethod', __('Paymethod'), array('class'=>'control-label', 'for'=>'paymethod' ))?>
                <div class="controls">
                    <select name="paymethod" id="paymethod" class="input-xlarge" REQUIRED>
                        <option>paypal</option>
                        <option>paymill</option>
                        <option>transfer</option>
                        <option>cash</option>
                    </select>
                </div>
            </div>

<div class="control-group">
                <?= FORM::label('product', __('Product'), array('class'=>'control-label', 'for'=>'product' ))?>
                <div class="controls">
                    <select name="product" id="product" class="input-xlarge" REQUIRED>
                        <option></option>
                        <?foreach ($products as $p):?>
                            <option value="<?=$p->id_product?>"><?=$p->title?> <?=$p->final_price()?> <?=$p->currency?></option>
                        <?endforeach?>
                    </select>
                </div>
            </div>

<div class="control-group">
                <?= FORM::label('currency', __('Currency'), array('class'=>'control-label', 'for'=>'currency' ))?>
                <div class="controls">
                    <select name="currency" id="currency" class="input-xlarge" REQUIRED>
                        <option></option>
                        <?foreach ($currency as $curr):?>
                            <option <?=($curr=='USD')?'selected':''?> value="<?=$curr?>"><?=$curr?></option>
                        <?endforeach?>
                    </select>
                </div>
            </div>

    <div class="control-group ">
    <label for="formorm_amount" class="control-label"><?=__('Amount')?></label>    <div class="controls">
        <input type="text" id="formorm_amount" name="amount" value="">                         </div>
</div>


    <div class="control-group ">
    <label for="formorm_pay_date" class="control-label"><?=__('Pay Date')?></label>    <div class="controls">
        <input type="text" id="formorm_pay_date" name="pay_date" placeholder="YYYY-MM-DD" value="<?=date('Y-m-d H:i:s')?>">                         </div>
</div>


			<div class="form-actions">
				<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'order','action'=>'create'))))?>
			</div>
		</fieldset>
	<?= FORM::close()?>

</div>
<!--/well-->