<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Purchases')?></h1>
    
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
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
                        <tr>
                            <td><?=$order->id_order;?></td>
                            <td><?=$order->product->title?> <?=$order->product->version?></td>
                            <td><?=Date::format($order->pay_date);?></td>
                            <td><?=($order->support_date!=NULL)?Date::format($order->support_date):__('Without support');?></td>
                            <td><?=i18n::money_format($order->amount).' '.$order->currency;?></td>
                            <td>
                                <?if (core::config('product.reviews')==1 AND Theme::get('premium')==1):?>
                                    <a title="<?=__('Product Review')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'review','id'=>$order->id_order))?>" 
                                        class="btn btn-mini btn-warning">
                                        <i class="glyphicon glyphicon-star-empty"></i></a>
                                <?endif?>
                                <?if($order->product->has_file()==TRUE):?>
                                <a title="<?=__('Download')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order))?>" 
                                class="btn btn-mini btn-success">
                                <i class="glyphicon glyphicon-download"></i> <?=__('Download')?> <?=$order->product->version?></a>
                                <?endif?>
                                <?if ($order->licenses->count_all()>0):?>
                                    <button type="button" class="btn btn-mini btn-info btn-licenses" data-licenses="id_<?=$order->id_order;?>">
                                        <span class="glyphicon glyphicon-list-alt "></span>
                                    </button>
                                <?endif?>
                                <a title="<?=__('print order')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'order','id'=>$order->id_order))?>" 
                                class="btn btn-mini btn-success">
                                <i class="glyphicon glyphicon-print"></i> <?=$order->product->version?></a>
                            </td>
                            <?if ($order->licenses->count_all()>0):?>
                                <tr id="id_<?=$order->id_order;?>" style="display:none;">
                                    <td colspan="6">
                                        <table class="table table-striped custab">
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
                        </tr>
                    <?endforeach?> 
                </tbody>
            </table>
        </div>
    </div>
</div>
