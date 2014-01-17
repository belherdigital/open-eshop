<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('New Order')?></h1>
</div>

<div class=" well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'order','action'=>'create')), array('class'=>'form-horizontal product_form'))?>


        <div class="form-group ">
    <label for="formorm_paymethod" class="col-md-2"><?=__('Name')?></label>  <div class="col-md-5">
        <input type="text" id="formorm_paymethod" name="name" value="" >                            </div>
</div>

        <div class="form-group ">
    <label for="formorm_paymethod" class="col-md-2"><?=__('Email')?></label>  <div class="col-md-5">
        <input type="text" id="formorm_paymethod" name="email" value="" >                            </div>
</div>

<div class="form-group">
                <?= FORM::label('paymethod', __('Paymethod'), array('class'=>'col-md-3 control-label', 'for'=>'paymethod' ))?>
                <div class="col-md-5">
                    <select name="paymethod" id="paymethod" class="form-control" REQUIRED>
                        <option>paypal</option>
                        <option>paymill</option>
                        <option>transfer</option>
                        <option>cash</option>
                    </select>
                </div>
            </div>

<div class="form-group">
                <?= FORM::label('product', __('Product'), array('class'=>'col-md-3 control-label', 'for'=>'product' ))?>
                <div class="col-md-5">
                    <select name="product" id="product" class="form-control" REQUIRED>
                        <option></option>
                        <?foreach ($products as $p):?>
                            <option value="<?=$p->id_product?>"><?=$p->title?> <?=$p->final_price()?> <?=$p->currency?></option>
                        <?endforeach?>
                    </select>
                </div>
            </div>

<div class="form-group">
                <?= FORM::label('currency', __('Currency'), array('class'=>'col-md-3 control-label', 'for'=>'currency' ))?>
                <div class="col-md-5">
                    <select name="currency" id="currency" class="form-control" REQUIRED>
                        <option></option>
                        <?foreach ($currency as $curr):?>
                            <option <?=($curr=='USD')?'selected':''?> value="<?=$curr?>"><?=$curr?></option>
                        <?endforeach?>
                    </select>
                </div>
            </div>

    <div class="form-group ">
    <label for="formorm_amount" class="col-md-2"><?=__('Amount')?></label>    <div class="col-md-5">
        <input type="text" id="formorm_amount" name="amount" value="">                         </div>
</div>


    <div class="form-group ">
    <label for="formorm_pay_date" class="col-md-2"><?=__('Pay Date')?></label>    <div class="col-md-5">
        <input type="text" id="formorm_pay_date" name="pay_date" placeholder="YYYY-MM-DD" value="<?=date('Y-m-d H:i:s')?>">                         </div>
</div>

    <div class="form-group ">
    <label for="formorm_notes" class="col-md-2"><?=__('Notes')?></label>    <div class="col-md-5">
        <input type="text" id="formorm_notes" maxlength=245 name="notes" placeholder="Order notes 245 characters max" value="">                         </div>
</div>


			<div class="form-actions">
				<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-lg btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'order','action'=>'create'))))?>
			</div>
		</fieldset>
	<?= FORM::close()?>

</div>
<!--/well-->