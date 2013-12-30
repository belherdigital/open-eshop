<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=$title?></h1>

    <div class="btn-group">
        <a href="?status=-1" class="btn <?=(core::get('status')==-1)?'btn-primary':''?>">
            <?=__('All')?>
        </a>
        <?foreach (Model_Ticket::$statuses as $k => $v):?>
        <a href="?status=<?=$k?>" class="btn <?=(core::get('status')==$k)?'btn-primary':''?>">
            <?=$v?>
        </a>
        <?endforeach?>
    </div>

    <a class="btn btn-info pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'new'))?>">
        <?=__('New')?></a>
</div>


<table class="table table-striped">
    <thead>
         <tr>
            <th><?=__('Title')?></th>
            <th><?=__('Date')?></th>
            <th><?=__('Last Answer')?></th>
            <th><?=__('Status')?></th>
            <th>#</th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($tickets as $ticket):?>
        <tr class="<?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'error':''?><?=($ticket->status==Model_Ticket::STATUS_HOLD)?'warning':''?>">
            <td><?=$ticket->title;?></td>
            <td><?=$ticket->created?></td>
            <td><?=(empty($ticket->read_date))?__('None'):$ticket->read_date?></td>
            <td><?=(Model_Ticket::$statuses[$ticket->status])?></td>
            <td>
                <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>" class="btn btn-success">
                    <i class="icon-envelope icon-white"></i></a>
            </td>
        </tr>
        <?endforeach?>
    </tbody>

</table>