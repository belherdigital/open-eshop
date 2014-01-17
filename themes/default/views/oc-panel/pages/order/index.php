<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="form-inline" method="get" action="<?=URL::current();?>">
  	<div class="form-group pull-right">
  		<div class="">
	      	<input type="text" class="form-control search-query" name="email" placeholder="<?=__('email')?>" value="<?=core::get('email')?>">
		</div>
	</div>
</form>

<div class="page-header">
    
	<h1><?=__('Orders')?></h1>
	
	<a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>">
		<i class="glyphicon glyphicon-pencil"></i>
		<?=__('New')?>
	</a>				

</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
	            <th>#</th>
	            <th><?=__('User') ?></th>
	            <th><?=__('Product') ?></th>
	            <th><?=__('Amount') ?></th>
	            <th><?=__('Coupon') ?></th>
	            <th><?=__('Date') ?></th>
				<th><?=__('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?foreach($orders as $order):?>
				<tr id="tr<?=$order->pk()?>">
					
	                <td><?=$order->pk()?></td>
	                <td><a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$order->user->pk())) ?>">
	                    <?=$order->user->name?></a> - <?=$order->user->email?>
	                </td>
	                <td><a href="<?=Route::url('oc-panel', array('controller'=> 'product', 'action'=>'update','id'=>$order->product->pk())) ?>">
	                    <?=$order->product->title?></a></td>
	                <td><?=$order->amount.' '.$order->currency?></td>
	                <td><a href="<?=Route::url('oc-panel', array('controller'=> 'coupon', 'action'=>'update','id'=>$order->coupon->pk())) ?>">
	                    <?=$order->coupon->name?></a></td>
	                <td><?=$order->pay_date?></td>
					<td width="80px">
						<?if ($controller->allowed_crud_action('update')):?>
						<a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$order->pk()))?>">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
						<?endif?>
					</td>

				</tr>
			<?endforeach?>
		</tbody>
	</table>
</div>
<?=$pagination?>