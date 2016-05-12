<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Affiliate Panel')?></h1>
    <div class="col-xs-3 pl-0">
        <label><?=__('Select Product')?></label>
        <select name="affiliate_percentage" id="affiliate_percentage" data-user="<?=$user->id_user?>">
            <option></option>
            <?foreach ($products as $prod):?>
                <?var_dump($prod->title)?>
                <option value="<?=$prod->id_product?>"
                        data-price="<?=__('Buy Now')?> <?=$prod->formated_price()?>" 
                        data-url="<?=Route::url('product', array('seotitle'=>$prod->seotitle,'category'=>$prod->category->seoname)) ?>?aff=<?=$user->id_user?>"
                        data-embed="<?=Core::config('general.base_url')?>embed.js?v=<?=core::VERSION?>">
                        <?=$prod->title?> <strong>%<?=round($prod->affiliate_percentage,1)?></strong></option>
            <?endforeach?>
        </select>
    </div>
    <div class="clearfix"></div><br>
    <p><?=__('Your affiliate ID is')?> <?=$user->id_user?>, 
        <?=__('example link')?> <span class="affi-example-link"><a target="_blank" href="<?=Route::url('default')?>?aff=<?=$user->id_user?>"><?=Route::url('default')?>?aff=<?=$user->id_user?></a></span>
    </p>

    <br>
        <p><?=__('Button with overlay')?>:</p>
            <textarea id="embed_button" class="col-md-4" onclick="this.select()"><script src="<?=Core::config('general.base_url')?>embed.js?v=<?=core::VERSION?>"></script>
                <a class="oe_button" href="<?=Core::config('general.base_url')?>"><?=__('Buy Now')?></a></textarea>

            <div class="clearfix"></div>
        
        </p>
    <br>
        <p><?=__('Button without overlay')?>:</p>
            <textarea id="no_embed_button" class="col-md-4" onclick="this.select()"><a class="oe_button" href="<?=Core::config('general.base_url')?>"><?=__('Buy Now')?></a></textarea>

            <div class="clearfix"></div>
        </p>
    <h2><?=__('Total earned')?> <?=i18n::format_currency($total_earnings)?></h2>
    <?if($last_payment_date!==NULL):?>
    <h3><?=__('Since last payment')?> <?=i18n::format_currency($last_earnings)?> (<?=$last_payment_date?>)</h3>
    <?endif?>
    <?if ($due_to_pay>core::config('affiliate.payment_min')):?>
    <h3><?=__('Due to pay next cicle')?>: <?=i18n::format_currency($total_earnings)?></h3>
    <?endif?>
</div>

<form id="edit-profile" class="form-inline" method="post" action="">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><?=__('From')?></div>
            <input type="text" class="form-control" id="from_date" name="from_date" value="<?=$from_date?>" data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <span>-</span>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><?=__('To')?></div>
            <input type="text" class="form-control" id="to_date" name="to_date" value="<?=$to_date?>" data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><?=__('Filter')?></button>
</form>

<br>

<h6 class="text-center"><?=__('Commissions')?></h6>
<div>
    <?=Chart::line($stats_daily, array('height'  => 200,
                                       'width'   => 400,
                                       'options' => array('responsive' => true, 'maintainAspectRatio' => false, 'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %>')))?>
</div>

<h2><?=__('Commissions')?></h2>
<div class="table-responsive">
    <table class="table table-striped">
    <thead>
         <tr>
            <th>#</th>
            <th><?=__('Date')?></th>
            <th><?=__('Clear commission')?></th>
            <th><?=__('Date Paid')?></th>
            <th><?=__('Product')?></th>
            <th><?=__('Commission')?></th>
            <th><?=__('Status')?></th>
            
        </tr>
    </thead>

    <tbody>
        <?foreach ($commissions as $c):?>
            <tr>
                <td><?=$c->id_affiliate?></td>
                <td><?=$c->created?></td>
                <td><?=$c->date_to_pay?></td>
                <td><?=$c->date_paid?></td>
                <td><?=$c->product->title?></td>
                <td><?=i18n::format_currency($c->amount, $c->currency)?></td>
                <td><?=Model_Affiliate::$statuses[$c->status]?></td>
            </tr>
        <?endforeach?> 
        </tbody>

    </table>
</div>

<?=$pagination?>

<?if(count($payments)):?>
<h2><?=__('Payments')?></h2>
<div class="table-responsive">
    <table class="table table-striped">
    <thead>
         <tr>
            <th>#</th>
            <th><?=__('Method')?></th>
            <th><?=__('Date')?></th>
            <th><?=__('Amount')?></th>
            <th><?=__('Status')?></th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($payments as $p):?>
            <tr>
                <td><?=$p->id_order?></td>
                <td><?=$p->paymethod?></td>
                <td><?=$p->pay_date?></td>
                <td><?=i18n::format_currency($p->amount, $p->currency)?></td>
                <td><?=Model_Order::$statuses[$p->status]?></td>
            </tr>
        <?endforeach?> 
        </tbody>

    </table>
</div>
<?endif?>


<p><?=__('Payout of commissions is after')?> <?=core::config('affiliate.payment_days')?> 
    <?=__('days and reached')?> <?=core::config('affiliate.payment_min')?> USD.
    <?=__('Affiliate cookie lasts')?> <?=core::config('affiliate.cookie')?> <?=__('days')?>.
<?if (core::config('affiliate.tos')):?>
<a href="<?=Route::url('page',array('seotitle'=>core::config('affiliate.tos')))?>" target="_blank"><?=__('Affiliate terms')?></a>.
<?endif?>
</p>