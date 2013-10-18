<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Support Tickets')?></h1>
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
            <td><?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
                <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>" class="btn btn-success"><i class="icon-envelope icon-white"></i></a>
                <?endif?>
            </td>
        </tr>
        <?endforeach?>
    </tbody>

</table>