<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('New Order')?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'order','action'=>'create')), array('class'=>'form-horizontal product_form'))?>
                    <div class="form-group ">
                        <label for="formorm_paymethod" class="col-md-4 control-label"><?=__('Name')?></label> 
                        <div class="col-md-8">
                            <input type="text" id="formorm_paymethod" name="name" value="" >
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="formorm_paymethod" class="col-md-4 control-label"><?=__('Email')?></label>
                        <div class="col-md-8">
                            <input type="text" id="formorm_paymethod" name="email" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('paymethod', __('Paymethod'), array('class'=>'col-md-4 control-label', 'for'=>'paymethod' ))?>
                        <div class="col-md-8">
                            <select name="paymethod" id="paymethod" class="form-control" REQUIRED>
                                <option>paypal</option>
                                <option>paymill</option>
                                <option>transfer</option>
                                <option>cash</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('product', __('Product'), array('class'=>'col-md-4 control-label', 'for'=>'product' ))?>
                        <div class="col-md-8">
                            <select name="product" id="product" class="form-control" REQUIRED>
                                <option></option>
                                <?foreach ($products as $p):?>
                                    <option value="<?=$p->id_product?>"><?=$p->title?> <?=$p->formated_price()?></option>
                                <?endforeach?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label('currency', __('Currency'), array('class'=>'col-md-4 control-label', 'for'=>'currency' ))?>
                        <div class="col-md-8">
                            <select name="currency" id="currency" class="form-control" REQUIRED>
                                <option></option>
                                <?foreach ($currency as $curr):?>
                                    <option <?=($curr=='USD')?'selected':''?> value="<?=$curr?>"><?=$curr?></option>
                                <?endforeach?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formorm_amount" class="col-md-4 control-label"><?=__('Amount')?></label>
                        <div class="col-md-8">
                            <input type="text" id="formorm_amount" name="amount" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formorm_pay_date" class="col-md-4 control-label"><?=__('Pay Date')?></label>
                        <div class="col-md-8">
                            <input type="text" id="formorm_pay_date" name="pay_date" placeholder="YYYY-MM-DD" value="<?=date('Y-m-d H:i:s')?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formorm_notes" class="col-md-4 control-label"><?=__('Notes')?></label>
                        <div class="col-md-8">
                            <input type="text" id="formorm_notes" maxlength=245 name="notes" placeholder="Order notes 245 characters max" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'order','action'=>'create'))))?>
                        </div>
                    </div>
                <?= FORM::close()?>
            </div>
        </div>
    </div>
</div>
