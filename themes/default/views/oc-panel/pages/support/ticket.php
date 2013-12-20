<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
		<h1><?=$ticket->title?></h1>
        <p><?=Text::bb2html($ticket->description,TRUE)?></p>
        <p>
        <?=$ticket->created?>

        <?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
        <a class="btn btn-warning pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'close','id'=>$ticket->id_ticket))?>">
        <?=__('Close Ticket')?></a>
        <?endif?> 

        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
        <form class="form-inline pull-right" method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>"> 
            <?= FORM::select('agent', $users, $ticket->id_user_support, array( 
                'id' => 'agent', 
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                ))?> 
            <button type="submit" class="btn"><?=__('Assign')?></button>
        </form>
        <?endif?> 

        </p>    
	</div>

    <?foreach ($replies as $reply):?>
    <div class="row">
        <div class="span2">
            <img src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($reply->user->email)));?>?s=100">
            <p>
                <?=$reply->user->name?><br>
                <?=$reply->created?>
            </p>
        </div>
        <div class="span6">
            <p><?=Text::bb2html($reply->description,TRUE)?></p>
        </div>
        <div class="span8"><hr></div>
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