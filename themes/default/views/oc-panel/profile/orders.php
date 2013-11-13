<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Purchases')?></h1>
    
</div>


<table class="table table-striped">
    <thead>
         <tr>
            <th>#</th>
            <th><?=__('Product')?></th>
            <th><?=__('Purchased')?></th>
            <th><?=__('Support until')?></th>
            <th><?=__('Price')?></th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($orders as $order):?>
        <tr class="info">
            <td><?=$order->id_order;?></td>
            <td><?=$order->product->title?> <?=$order->product->version?></td>
            <td><?=Date::format($order->pay_date);?></td>
            <td><?=($order->support_date!=NULL)?Date::format($order->support_date):__('Without support');?></td>
            <td><?=i18n::money_format($order->amount).' '.$order->currency;?></td>
            <td>
                <?if(!empty($order->product->file_name)):?>
                <a title="<?=__('Download')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order))?>" 
                class="btn btn-mini btn-success">
                <i class="icon-download icon-white"></i> <?=__('Download')?> <?=$order->product->version?></a>
                <?endif?>
            </td>
        </tr>
        <?if ($order->licenses->count_all()>0):?>
        <tr>
            <td colspan="5">
                <table class="table table-striped">
                    <th><?=__('License')?></th>
                    <th><?=__('Created')?></th>
                    <th><?=__('Domain')?></th>
                <?foreach ($licenses as $license):?>
                    <?if($license->id_order == $order->id_order):?>
                    <tr>
                        <td><?=$license->license?></td>
                        <td><?=$license->created?></td>
                        <td><?=($license->status==Model_License::STATUS_NOACTIVE)?__('Inactive'):$license->domain?></td>
                    <tr>
                    <?endif?>
                <?endforeach?>
                </table>
            </td>
        </tr>
        <?endif?>
        <?endforeach?>
    </tbody>

</table>