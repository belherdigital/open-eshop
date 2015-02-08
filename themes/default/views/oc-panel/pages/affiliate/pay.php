<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Pay Affiliates')?></h1>
    <h2><?=__('Total to Pay')?> <?=i18n::format_currency($total_to_pay)?></h2>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                 <tr>
                    <th>#</th>
                    <th><?=__('User')?></th>
                    <th><?=__('Email')?></th>
                    <th>Paypal</th>
                    <th><?=__('Commission')?></th>
                    <th><?=__('Actions')?></th><th></th>
                </tr>
            </thead>
        
            <tbody>
                <?foreach ($users as $u):?>
                    <tr>
                        <td>
                            <a class="btn btn-warning" title="<?=__('Affiliate stats')?>" href="<?=Route::url('oc-panel', array('controller'=> 'profile', 'action'=>'affiliate','id'=>$u->id_user)) ?>">
                                <i class="glyphicon glyphicon-list"></i>
                            </a>
                        </td>
                        <td>
                            <a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$u->id_user))?>">
                                <?=$u->name?></a>
                        </td>
                        <td><?=$u->email?></td>
                        <td><?=$u->paypal_email?></td>
                        <td><?=i18n::format_currency($users_to_pay[$u->id_user]['total'])?></td>
                        <?if (Valid::email($u->paypal_email)):?>
                        <td>
                            <a target="_blank"href="https://www.paypal.com/cgi-bin/webscr?business=<?=$u->paypal_email?>&cmd=_xclick&currency_code=USD&amount=<?=round($users_to_pay[$u->id_user]['total'],2)?>&item_name=Commissions_<?=date('Y-m-d')?>">Paypal</a>
                        </td>
                        <td width="80" style="width:80px;">
                            <a
                                href="<?=Route::url('oc-panel', array('controller'=>'affiliate', 'action'=>'pay','id'=>$u->id_user))?>" 
                                class="btn btn-primary" 
                                title="<?=__('Mark as Paid')?>" 
                                data-toggle="confirmation" 
                                data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <i class="glyphicon glyphicon-usd"></i>
                            </a>
                        </td>
                        <?endif?>
                    </tr>
                <?endforeach?> 
                </tbody>
        
            </table>
        </div>
    </div>
</div>