<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="form-inline pull-right" method="get" action="<?=URL::current();?>">
  	<div class="form-group">
  		<input type="text" class="form-control" name="email" placeholder="<?=__('Email')?>" value="<?=core::get('email')?>">
  	</div>
  	<button type="submit" class="btn"><?=__('Search')?></button>
</form>
<div class="page-header">
	<h1><?=ucfirst(__($name))?></h1>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <?foreach($fields as $field):?>
                            <th><?=ucfirst((method_exists($orm = ORM::Factory($name), 'formo') ? Arr::path($orm->formo(), $field.'.label', __($field)) : __($field)))?></th>
                        <?endforeach?>
                        <?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
                        <th><?=__('Actions') ?></th>
                        <?endif?>
                    </tr>
                </thead>
                <tbody>
                    <?foreach($elements as $element):?>
                        <tr id="tr<?=$element->pk()?>">
        
                            <td><?=$element->id_download?></td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'update','id'=>$element->order->pk())) ?>">
                                <?=$element->order->product->title?> - <?=round($element->order->amount,2)?><?=$element->order->currency?> <?=Date::format($element->order->pay_date,'d-m-y')?>
                            </td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$element->user->pk())) ?>">
                                <?=$element->user->name?></a> - <?=$element->user->email?>
                            </td>
                            <td><a target="_blank" href="http://www.ipgetinfo.com/?mode=ip&lang=en&ip=<?=long2ip($element->ip_address)?>"><?=long2ip($element->ip_address)?></a></td>
                            <td><?=$element->created?></td>
                            <?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
                            <td width="80" style="width:80px;">
                                <?if ($controller->allowed_crud_action('update')):?>
                                <a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$element->pk()))?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?endif?>
                                <?if ($controller->allowed_crud_action('delete')):?>
                                <a data-text="<?=__('Are you sure you want to delete?')?>" 
                                    data-id="tr<?=$element->pk()?>" class="btn btn-danger index-delete" title="<?=__('Delete')?>" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete','id'=>$element->pk()))?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <?endif?>
                            </td>
                            <?endif?>
                        </tr>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-center"><?=$pagination?></div>