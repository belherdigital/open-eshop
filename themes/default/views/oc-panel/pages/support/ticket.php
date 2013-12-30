<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
        <h1><?=$ticket->title?> - <?=$ticket->product->title?></h1>
        <h3><?=$ticket->user->name?> <?=Date::fuzzy_span(Date::mysql2unix($ticket->created))?></h3>

        <?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
        <a class="btn btn-warning pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'close','id'=>$ticket->id_ticket))?>">
        <?=__('Close Ticket')?></a>
        <?endif?> 

        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
    
            <a href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'update','id'=>$ticket->order->pk())) ?>">
                <?=round($ticket->order->amount,2)?><?=$ticket->order->currency?> <?=Date::format($ticket->order->pay_date,'d-m-y')?>
            </a>

            <br>
            <a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$ticket->id_user)) ?>">
                <?=$ticket->user->email?>
            </a>

            <?if ($ticket->order->licenses->count_all()>0):?>
            <?foreach ($ticket->order->licenses->find_all() as $license):?>
                <br>
                <a href="<?=Route::url('oc-panel', array('controller'=> 'license', 'action'=>'update','id'=>$license->id_license)) ?>">
                    <?=($license->status==Model_License::STATUS_NOACTIVE)?__('Inactive'):'http://'.$license->domain?>
                </a>
            <?endforeach?>
            <?endif?>

            <form class="form-inline pull-right" method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>"> 
                <?= FORM::select('agent', $users, $ticket->id_user_support, array( 
                    'id' => 'agent', 
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    ))?> 
                <button type="submit" class="btn btn-info"><?=__('Assign')?></button>
            </form>
        <?endif?> 
	</div>

    <div class="row">
        <div class="span2">
            <img src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($ticket->user->email)));?>?s=100">
            <p>
                <?=$ticket->user->name?><br>
                <?=Date::fuzzy_span(Date::mysql2unix($ticket->created))?><br>
                <?=$ticket->created?>
            </p>
        </div>
        <div class="span6">
            <p><?=Text::bb2html($ticket->description,TRUE)?></p>
        </div>
    </div>

    <?foreach ($replies as $reply):?>
    <div class="row <?=($ticket->id_user!==$reply->id_user)?'alert alert-warning':''?>">
        <div class="span2">
            <img src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($reply->user->email)));?>?s=100">
            <p>
                <?=$reply->user->name?><br>
                <?=Date::fuzzy_span(Date::mysql2unix($reply->created))?><br>
                <?=$reply->created?>
            </p>
        </div>
        <div class="span6">
            <p><?=Text::bb2html($reply->description,TRUE)?></p>
        </div>
    </div>
    <?endforeach?>

    <?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>">         
      <?php if ($errors): ?>
        <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
        <ul class="errors">
        <?php foreach ($errors as $message): ?>
            <li><?php echo $message ?></li>
        <?php endforeach ?>
        </ul>
        <?php endif ?>       


      <div class="control-group">
        <label class="control-label"><?=__("Reply")?>:</label>
        <div class="controls">
        <textarea name="description" rows="10" class="span6" required></textarea>
        </div>
      </div>

                
      <div class="form-actions">
      	<a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index'))?>" class="btn"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Reply')?></button>
      </div>
	</form>  
    <?endif?>  