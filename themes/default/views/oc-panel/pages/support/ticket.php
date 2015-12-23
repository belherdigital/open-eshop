<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$ticket->title?></h1>
    <p><?=$ticket->user->name?> <?=Date::fuzzy_span(Date::mysql2unix($ticket->created))?> - <?=$ticket->product->title?></p>

    <a class="btn btn-default pull-right" id="collapse-all-tickets"><?=__('Collapse')?> <i class="glyphicon glyphicon-chevron-down"></i></a>

    <?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
    <a class="btn btn-warning pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'close','id'=>$ticket->id_ticket))?>">
    <?=__('Close Ticket')?></a>
    <?endif?> 

    <?if(Auth::instance()->get_user()->has_access('supportadmin')):?>

        <form class="form-inline pull-right" method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>"> 
            <?= FORM::select('agent', $users, $ticket->id_user_support, array( 
                'id' => 'agent', 
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'class'=>'form-control',
                ))?> 
            <button type="submit" class="btn btn-info"><?=__('Assign')?></button>
        </form>

        <a target="_blank" href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'update','id'=>$ticket->order->pk())) ?>">
            <?=round($ticket->order->amount,2)?><?=$ticket->order->currency?> <?=Date::format($ticket->order->pay_date,'d-m-y')?>
        </a>

        <br>
        <a target="_blank" href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$ticket->id_user)) ?>">
            <?=$ticket->user->email?>
        </a>
        - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index','id'=>'admin'))?>?search=<?=$ticket->user->email?>">
            <?=__('Tickets')?></a>
        - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'index'))?>?filter__id_user=<?=$ticket->user->email?>">
            <?=__('Orders')?></a>


        <?if ($ticket->order->licenses->count_all()>0):?>
        <?foreach ($ticket->order->licenses->find_all() as $license):?>
            <br>
            <a target="_blank" href="http://<?=$license->domain?>" target="_blank">
                <?=$license->domain?>
            </a>
            -
            <a target="_blank" href="<?=Route::url('oc-panel', array('controller'=> 'license', 'action'=>'update','id'=>$license->id_license)) ?>">
                <?=(empty($license->domain))?__('Inactive license'):__('Active license')?>
            </a>
        <?endforeach?>
        <?endif?>
    <?endif?> 

     <div class="clearfix"></div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 user-ticket">
                <div class="dropdown-user invisible pull-right btn btn-primary btn-xs" data-for=".<?=HTML::chars($ticket->user->name)?>">
                    <i class="glyphicon glyphicon-chevron-down"></i>
                </div>
                <div class="<?=HTML::chars($ticket->user->name)?> short-text">
                    <div class="col-md-2 pull-left">
                        <div class="pull-left">
                            <span class="text-muted"><?=$ticket->user->name?></span><br>
                            <span class="text-muted"><?=$ticket->created?></span>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9 ">
                        <p><?=Text::limit_chars(Text::bb2html($ticket->description,TRUE), 100, NULL, TRUE)?></p>
                    </div>
                </div>
                <div class="<?=HTML::chars($ticket->user->name)?> user-infos long-text">
                    <div class="col-md-2 pull-left">
                        <img class="ticket_image img-circle" src="<?=$ticket->user->get_profile_image()?>" style="max-width:120px; max-height:120px;">
                        <div class="pull-left">
                            <span class="text-muted"><?=$ticket->user->name?></span><br>
                            <span class="text-muted"><?=Date::fuzzy_span(Date::mysql2unix($ticket->created))?></span><br>
                            <span class="text-muted"><?=$ticket->created?></span>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9 ">
                        <p><?=Text::bb2html($ticket->description,TRUE)?></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><hr>
            
            <?foreach ($replies as $reply):?>
            <div class="col-md-12 user-ticket">
                <div class="dropdown-user invisible pull-right btn btn-primary btn-xs" data-for=".<?=HTML::chars($reply->user->name).'_'.$reply->id_ticket?>">
                    <i class="glyphicon glyphicon-chevron-down"></i>
                </div>
                <div class="<?=HTML::chars($reply->user->name).'_'.$reply->id_ticket?> short-text">
                    <div class="col-md-2 pull-left">
                        <div class="pull-left">
                            <span class="text-muted"><?=$reply->user->name?></span><br>
                            <span class="text-muted"><?=$reply->created?></span>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9 ">
                        <p><?=Text::limit_chars(Text::removebbcode($reply->description,TRUE), 100, NULL, TRUE)?></p>
                    </div>
                </div>
                <div class="<?=HTML::chars($reply->user->name).'_'.$reply->id_ticket?> user-infos long-text " >
                    <div class="col-md-2">
                        <img class="ticket_image img-circle" src="<?=$reply->user->get_profile_image()?>" style="max-width:120px; max-height:120px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-0">
                            <span class="text-muted"><?=$reply->user->name?></span><br>
                            <span class="text-muted"><?=Date::fuzzy_span(Date::mysql2unix($reply->created))?></span><br>
                            <span class="text-muted"><?=$reply->created?></span><br>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <p><?=Text::bb2html($reply->description,TRUE)?></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div><hr>
            <?endforeach?>
            
            <?if($ticket->status!=Model_Ticket::STATUS_CLOSED OR Auth::instance()->get_user()->has_access('supportadmin')):?>
            
                <?if($ticket->status==Model_Ticket::STATUS_CLOSED):?>
                    <div class="alert alert-warning" role="alert">
                    <?=__('This ticket is closed, by replying you will reopen the ticket.')?>
                    </div>
                <?endif?>
            
                <form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>">         
            
                  <?php if ($errors): ?>
                    <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
                    <ul class="errors">
                    <?php foreach ($errors as $message): ?>
                        <li><?php echo $message ?></li>
                    <?php endforeach ?>
                    </ul>
                    <?php endif ?>       
            
            
                  <div class="form-group">
                    <label class="col-md-2"><?=__("Reply")?>:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                    <textarea id="description" name="description" rows="10" class="form-control" required><?=core::post('description')?></textarea>
                    </div>
                  </div>
            
                  <?=Form::token('reply_ticket')?>
                  <div class="form-actions">
                    <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index'))?>" class="btn btn-default"><?=__('Cancel')?></a>
                    <button type="submit" class="btn btn-primary"><?=__('Reply')?></button>
                  </div>
                </form>  
            
            <?endif?>  
            
            <br><br>
        </div>
    </div>
</div>