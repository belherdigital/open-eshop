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
            <th><?=__('Read')?></th>
            <th><?=__('Status')?></th>
            <th>#</th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($tickets as $ticket):?>
        <tr class="">
            <td><?=$ticket->title;?></td>
            <td><?=$ticket->created?></td>
            <td><?=(empty($ticket->read_date))?__('Unread'):$ticket->read_date?></td>
            <td><?=(Model_Ticket::$statuses[$ticket->status])?></td>
            <td><a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>" class="btn btn-success"><i class="icon-envelope icon-white"></i></a></td>
        </tr>
        <?endforeach?>
    </tbody>

</table>