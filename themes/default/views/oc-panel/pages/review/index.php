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
    <?if (Theme::get('premium')!=1):?>
    <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
        <?=__('Product reviews is only available with premium themes!').'<br/>'.__('Upgrade your Open eShop site to activate this feature.')?>
        <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
    </p>
    <?endif?>
</div>

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
					<td width="80px">
						<?if ($controller->allowed_crud_action('update')):?>
						<a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$review->pk()))?>">
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