<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=$title?></h1>

    <div class="btn-group">
        <a href="?status=-1" class="btn <?=(core::get('status',-1)==-1)?'btn-primary':''?>">
            <?=__('All')?>
        </a>
        <?foreach (Model_Ticket::$statuses as $k => $v):?>
        <a href="?status=<?=$k?>" class="btn <?=(core::get('status',-1)==$k)?'btn-primary':''?>">
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
            <th><?=__('Agent')?></th>
            <th span="2"></th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($tickets as $ticket):?>
        <tr class="<?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'error':''?>
            <?=($ticket->status==Model_Ticket::STATUS_HOLD)?'warning':''?>
            <?=($ticket->status==Model_Ticket::STATUS_READ)?'success':''?>">
            <td><?=$ticket->title;?></td>
            <td><?=$ticket->created?></td>
            <td><?=(empty($ticket->read_date))?__('None'):$ticket->read_date?></td>
            <td><?=(!$ticket->agent->loaded())?__('None'):$ticket->agent->name?></td>
            <td><span class="label <?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'label-important':''?>
                                    <?=($ticket->status==Model_Ticket::STATUS_CREATED)?'label-info':''?>
                                    <?=($ticket->status==Model_Ticket::STATUS_READ)?'label-success':''?>">
                <?=(Model_Ticket::$statuses[$ticket->status])?></span>
            </td>
            <td>
                <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>" class="btn btn-success">
                    <i class="icon-envelope icon-white"></i></a>
            </td>
        </tr>
        <?endforeach?>
    </tbody>

</table>

<?=$pagination?>