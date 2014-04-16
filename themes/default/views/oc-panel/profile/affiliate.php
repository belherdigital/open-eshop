<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Affiliate Panel')?></h1>
    <p><?=__('Your affiliate ID is')?> <?=$user->id_user?>, 
        <?=__('example link')?> <a target="_blank" href="<?=Route::url('default')?>?aff=<?=$user->id_user?>"><?=Route::url('default')?>?aff=<?=$user->id_user?></a>
    </p>
    <h2><?=__('Total')?>: <?=i18n::format_currency($total_earnings)?></h2>
    <?if($last_payment_date!==NULL):?>
    <h3><?=__('Since last payment')?> <?=$last_payment_date?> <?=i18n::format_currency($last_earnings)?></h3>
    <?endif?>
    <?if ($due_to_pay>core::config('affiliate.payment_min')):?>
    <h3><?=__('Due to pay next cicle')?>: <?=i18n::format_currency($total_earnings)?></h3>
    <?endif?>
</div>


<form id="edit-profile" class="form-inline" method="post" action="">
    <fieldset>
    <div class="col-md-3 pl-0">
        <label><?=__('From')?></label>
        <input  type="text" class="col-md-2" size="16"
                id="from_date" name="from_date"  value="<?=$from_date?>"  
                data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
        </div>
        <div class="col-md-3 pl-0">
        <label><?=__('To')?></label>
        <input  type="text" class="col-md-2" size="16"
                id="to_date" name="to_date"  value="<?=$to_date?>"  
                data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
        </div>
        <div class="col-md-3 pl-0">
        <label for=""></label>
        <button type="submit" class="btn btn-primary mt25"><?=__('Filter')?></button>
        </div> 
    
    </fieldset>
</form>

<?=Chart::column($stats_daily,array('title'=>__('Commissions per day'),
                                    'height'=>200,
                                    'width'=>'100%',
                                    'series'=>'{0:{targetAxisIndex:1, visibleInLegend: true}}'))?>

<div class="table-responsive">
    <table class="table table-striped">
    <thead>
         <tr>
            <th>#</th>
            <th><?=__('Date')?></th>
            <th><?=__('Expected payment')?></th>
            <th><?=__('Paid')?></th>
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


<p><?=__('Payout of commissions is after')?> <?=core::config('affiliate.payment_days')?> 
    <?=__('days and reached')?> <?=core::config('affiliate.payment_min')?> USD.
    <?=__('Affiliate cookie lasts')?> <?=core::config('affiliate.cookie')?> <?=__('days')?>.
<?if (core::config('affiliate.tos')):?>
<a href="<?=Route::url('page',array('seotitle'=>core::config('affiliate.tos')))?>" target="_blank"><?=__('Affiliate terms')?></a>.
<?endif?>
</p>