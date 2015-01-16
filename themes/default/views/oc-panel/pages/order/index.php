<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (Core::get('print')!=1):?>
<div class="page-header">
    <form class="form-inline" method="get" action="<?=URL::current();?>">
        <div class="form-group pull-right">
            <div class="">
                
                <form id="edit-profile" class="form-inline" method="post" action="">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('From')?></div>
                            <input type="text" class="form-control" id="from_date" name="from_date" value="<?=core::request('from_date')?>" data-date="<?=core::request('from_date')?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('To')?></div>
                            <input type="text" class="form-control" id="to_date" name="to_date" value="<?=core::request('to_date')?>" data-date="<?=core::request('to_date')?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <select name="id_product" id="id_product" class="form-control" REQUIRED>
                        <option><?=__('Product')?></option>
                        <?foreach ($products as $p):?>
                            <option value="<?=$p->id_product?>" <?=(core::request('id_product')==$p->id_product)?'SELECTED':''?> ><?=$p->title?></option>
                        <?endforeach?>
                    </select>
                    <input type="text" class="form-control search-query" name="email" placeholder="<?=__('email')?>" value="<?=core::request('email')?>">
                    <button type="submit" class="btn btn-primary"><?=__('Filter')?></button>
                    <a class="btn btn-xs btn-warning" href="<?=Route::url('oc-panel', array('controller'=>'order', 'action'=>'index'))?>">
                        <?=__('Reset')?>
                    </a>
                </form>
                
            </div>
        </div>
    </form>

    <h1><?=__('Orders')?></h1>	

    <a class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>">
        <i class="glyphicon glyphicon-pencil"></i>
        <?=__('New Order')?>
    </a>
	
</div>
<?endif?>

<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
	            <th>#</th>
	            <th><?=__('Name') ?></th>
                <th><?=__('Email') ?></th>
                <th><?=__('Country') ?></th>
	            <th><?=__('Product') ?></th>
	            <th><?=__('Amount') ?></th>
                <th><?=__('VAT') ?></th>
	            <th><?=__('Coupon') ?></th>
	            <th><?=__('Paid') ?></th>
                <?if (Core::get('print')!=1):?>
				<th><?=__('Actions') ?></th>
                <?endif?>
			</tr>
		</thead>
		<tbody>
			<?foreach($orders as $order):?>
				<tr id="tr<?=$order->pk()?>">
	                <td><?=$order->pk()?></td>
	                <td>
                        <a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$order->user->pk())) ?>">
	                    <?=$order->user->name?>
                        </a> 
	                </td>
                    <td>
                        <a href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'index'))?>?email=<?=$order->user->email?>">
                        <?=$order->user->email?>
                        </a>
                    </td>
                    <td><?=$order->country?></td>
	                <td>
                        <a href="<?=Route::url('oc-panel', array('controller'=>'order', 'action'=>'index')).URL::query(array('id_product'=>$order->product->pk()))?>">
	                    <?=$order->product->title?>
                        </a>
                    </td>
	                <td><?=i18n::format_currency($order->amount, $order->currency)?></td>
                    <td><?=round($order->VAT,1)?>%</td>
	                <td>
                        <a href="<?=Route::url('oc-panel', array('controller'=>'order', 'action'=>'index')).URL::query(array('id_coupon'=>$order->coupon->pk()))?>">
	                    <?=$order->coupon->name?>
                        </a>
                    </td>
	                <td><?=$order->pay_date?></td>
                    <?if (Core::get('print')!=1):?>
					<td width="80" style="width:80px;">
						<?if ($controller->allowed_crud_action('update')):?>
						<a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$order->pk()))?>">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
						<?endif?>
					</td>
                    <?endif?>

				</tr>
			<?endforeach?>
		</tbody>
	</table>
</div>
<?=$pagination?>


<?if( ! core::get('print')):?>
    <div class="pull-right">
        <a target="_blank" class="btn btn-xs btn-success" title="<?=__('Print this')?>" href="<?=Route::url('oc-panel', array('controller'=>'order', 'action'=>'index')).URL::query(array('print'=>1))?>"><i class="glyphicon glyphicon-print"></i><?=__('Print this')?></a>
    </div>
<?endif;?>