<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'pay')) ?>">
    <i class="glyphicon glyphicon-usd"></i>
    <?=__('Pay Affiliates')?>
</a>    
<a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'settings', 'action'=>'affiliates')) ?>">
    <i class="glyphicon glyphicon-cog"></i>
    <?=__('Affiliates Config')?>
</a>

<form class="form-inline" method="get" action="<?=URL::current();?>">
    <div class="form-group pull-right">
        <div class="">
            <input type="text" class="form-control search-query" name="email" placeholder="<?=__('email')?>" value="<?=core::get('email')?>">
        </div>
    </div>
</form>

<div class="page-header">
    <h1><?=__('Affiliate Panel')?></h1>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                 <tr>
                    <th>#</th>
                    <th><?=__('Order')?></th>
                    <th><?=__('User')?></th>
                    <th><?=__('Date')?></th>
                    <th><?=__('Expected payment')?></th>
                    <th><?=__('Paid')?></th>
                    <th><?=__('Product')?></th>
                    <th><?=__('Commission')?></th>
                    <th><?=__('Status')?></th>
                    <th><?=__('Actions')?></th>
                </tr>
            </thead>
        
            <tbody>
                <?foreach ($commissions as $c):?>
                    <tr>
                        <td>
                            <a class="btn btn-warning" title="<?=__('Affiliate stats')?>" href="<?=Route::url('oc-panel', array('controller'=> 'profile', 'action'=>'affiliate','id'=>$c->id_affiliate)) ?>">
                                <i class="glyphicon glyphicon-list"></i>
                            </a>
                        </td>
                        <td>
                            <a href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'update','id'=>$c->order->id_order)) ?>">
                                <?=$c->order->id_order?></a>
                        </td>
                        <td>
                            <a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$c->user->pk())) ?>">
                                <?=$c->user->name?></a> - <?=$c->user->email?> 
                        </td>
                        <td><?=$c->created?></td>
                        <td><?=$c->date_to_pay?></td>
                        <td><?=$c->date_paid?></td>
                        <td><?=$c->product->title?></td>
                        <td><?=i18n::format_currency($c->amount, $c->currency)?> (<?=round($c->percentage,1)?>%)</td>
                        <td><?=Model_Affiliate::$statuses[$c->status]?></td>
        				<td width="80" style="width:80px;">
                            <?if ($controller->allowed_crud_action('update')):?>
                            <a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$c->pk()))?>">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <?endif?>
                        </td>
                    </tr>
                <?endforeach?> 
                </tbody>
        
            </table>
        </div>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>


<p><?=__('Payout of commissions is after')?> <?=core::config('affiliate.payment_days')?> 
    <?=__('days and reached')?> <?=core::config('affiliate.payment_min')?> USD.
    <?=__('Affiliate cookie lasts')?> <?=core::config('affiliate.cookie')?> <?=__('days')?>.
<?if (core::config('affiliate.tos')):?>
<a href="<?=Route::url('page',array('seotitle'=>core::config('affiliate.tos')))?>" target="_blank"><?=__('Affiliate terms')?></a>.
<?endif?>
</p>