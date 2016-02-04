<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="form-inline" method="get" action="<?=URL::current();?>">
    <div class="form-group pull-right">
        <div class="">
            <input type="text" class="form-control search-query" name="search" placeholder="<?=__('search')?>" value="<?=core::get('search')?>">
        </div>
    </div>
</form>

<div class="page-header">
	<h1><?=$title?></h1>
    <p><a target="_blank" href="https://docs.open-eshop.com/support/" target="_blank"><?=__('Read more')?></a></p>
    <br>
    <div class="btn-group">
        <a href="?status=-1" class="btn <?=(core::get('status',-1)==-1)?'btn-primary':'btn-default'?>">
            <?=__('All')?>
        </a>
        <?foreach (Model_Ticket::$statuses as $k => $v):?>
        <a href="?status=<?=$k?>" class="btn <?=(core::get('status',-1)==$k)?'btn-primary':'btn-default'?>">
            <?=$v?>
        </a>
        <?endforeach?>

        <?if(Auth::instance()->get_user()->has_access('supportadmin') AND core::get('status')==Model_Ticket::STATUS_HOLD):?>
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'massclose'))?>" 
            class="btn btn-warning" 
            title="<?=__('Close holded tickets without answer in 1 month?')?>" 
            data-toggle="confirmation" 
            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
            data-btnCancelLabel="<?=__('No way!')?>">
            <?=__('Close Old Tickets')?>
        </a>
        <?endif?>
    </div>

    <a class="btn btn-info pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'new'))?>">
        <i class="glyphicon glyphicon-envelope"></i> <?=__('New Ticket')?></a>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                     <tr>
                        <th><?=__('Title')?></th>
                        <th><?=__('Date')?></th>
                        <th><?=__('Last Answer')?></th>
                        <th><?=__('Agent')?></th>
                        <th ></th>
                    </tr>
                </thead>
        
                <tbody>
                    <?foreach ($tickets as $ticket):?>
                    <tr class="<?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'danger':''?>
                        <?=($ticket->status==Model_Ticket::STATUS_HOLD)?'warning':''?>
                        <?=($ticket->status==Model_Ticket::STATUS_READ)?'success':''?>">
                        <td><span class="ww"><?=($ticket->title!='')?$ticket->title:Text::limit_chars(Text::removebbcode($ticket->description), 45, NULL, TRUE);?></span></td>
                        <td><?=$ticket->created?></td>
                        <td><?=(empty($ticket->read_date))?__('None'):$ticket->read_date?></td>
                        <td><?=(!$ticket->agent->loaded())?__('None'):$ticket->agent->name?></td>
                        <td><span class="label <?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'label-danger':''?>
                                                <?=($ticket->status==Model_Ticket::STATUS_CREATED)?'label-info':''?>
                                                <?=($ticket->status==Model_Ticket::STATUS_READ)?'label-success':''?>
                                                <?=($ticket->status==Model_Ticket::STATUS_HOLD)?'label-warning':''?>">
                            <?=(Model_Ticket::$statuses[$ticket->status])?></span>
                        </td>
                        <td>
                            <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>($ticket->id_ticket_parent!=NULL)?$ticket->id_ticket_parent:$ticket->id_ticket))?>" class="btn btn-success">
                                <i class="glyphicon glyphicon-envelope"></i></a>
                        </td>
                    </tr>
                    <?endforeach?>
                </tbody>
        
            </table>
        </div>
    </div>
</div>

<div class="text-center"><?=$pagination?></div>