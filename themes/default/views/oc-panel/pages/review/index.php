<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="form-inline" method="get" action="<?=URL::current();?>">
  	<div class="form-group pull-right">
  		<div class="">
	      	<input type="text" class="form-control search-query" name="email" placeholder="<?=__('email')?>" value="<?=core::get('email')?>">
		</div>
	</div>
</form>

<div class="page-header">
	<h1><?=__('Reviews')?></h1>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?=__('User') ?></th>
                        <th><?=__('Product') ?></th>
                        <th><?=__('Order') ?></th>
                        <th><?=__('Rate') ?></th>
                        <th><?=__('Date') ?></th>
                        <th><?=__('Edit') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?foreach($reviews as $review):?>
                        <?if ($review->user->loaded() AND $review->product->loaded()):?>
                        <tr id="tr<?=$review->pk()?>">
                            <td><?=$review->pk()?></td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$review->user->pk())) ?>">
                                <?=$review->user->name?></a> - <?=$review->user->email?>
                            </td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'product', 'action'=>'update','id'=>$review->product->pk())) ?>">
                                <?=$review->product->title?></a></td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'update','id'=>$review->order->pk())) ?>">
                                <?=$review->order->amount.' '.$review->order->currency?></a></td>
                            <td><?=$review->rate?></td>
                            <td><?=$review->created?></td>
                            <td width="80" style="width:80px;">
                                <?if ($controller->allowed_crud_action('update')):?>
                                <a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$review->pk()))?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?endif?>
                            </td>
                        </tr>
                        <?endif?>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>