<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Purchases')?></h1>
    
</div>


<table class="table table-striped">
    <thead>
         <tr>
            <th>#</th>
            <th><?=__('Product')?></th>
            <th><?=__('Date')?></th>
            <th><?=__('Price')?></th>
            <th><?=__('Download')?></th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($orders as $order):?>
        <tr>
            <th><?=$order->id_order;?></th>
            <td><?=$order->product->title?></td>
            <td><?=$order->pay_date;?></td>
            <td><?=$order->amount.' '.$order->currency;?></td>
            <td><a href="" class="btn btn-warning"><i class="icon-download icon-white"></i></a></td>
        </tr>
        <tr>
            <td colspan="4">
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
        <?endforeach?>
    </tbody>

</table>