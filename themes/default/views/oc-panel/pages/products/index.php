<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
	<h1><?=ucfirst(__($name))?></h1>
	<?if(Request::current()->controller() == 'content'):?>
	<a target='_blank' href='http://open-classifieds.com/2013/08/27/automatic-emails-sent-to-users/'><?=__('Automatic emails sent to users')?></a>
	<?endif?>
	<?if ($controller->allowed_crud_action('create')):?>
	<a class="btn btn-primary pull-right" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>">
		<i class="icon-pencil icon-white"></i>
		<?=__('New')?>
	</a>				
	<?endif?>
</div>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<?foreach($fields as $field):?>
				<th><?=ucfirst((method_exists($orm = ORM::Factory($name), 'formo') ? Arr::path($orm->formo(), $field.'.label', __($field)) : __($field)))?></th>
			<?endforeach?>
			<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
            <th><?=__('Price')?></th>
            <th><?=__('Sale URL')?></th>
			<th><?=__('Actions') ?></th>
			<?endif?>
		</tr>
	</thead>
	<tbody>
		<?foreach($elements as $element):?>
			<tr id="tr<?=$element->pk()?>">
				<?foreach($fields as $field):?>
					<td><?=html::chars($element->$field)?></td>
				<?endforeach?>
                <td>
                    <?=$element->price.' '.$element->currency?>
                </td>
                <td>
                    <a target="_blank" href="<?=Route::url('product', array('seotitle'=>$element->seotitle,'category'=>$element->category->seoname)) ?>">
                        <?=Route::url('product', array('seotitle'=>$element->seotitle,'category'=>$element->category->seoname)) ?>
                    </a>
                    <br>
                    <a target="_blank" href="<?=Route::url('product-minimal', array('seotitle'=>$element->seotitle)) ?>">
                        <?=Route::url('product-minimal', array('seotitle'=>$element->seotitle)) ?>
                    </a>
                </td>
				<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
				<td width="80px">
					<?if ($controller->allowed_crud_action('update')):?>
					<a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$element->pk()))?>">
						<i class="icon-edit icon-white"></i>
					</a>
					<?endif?>
					<?if ($controller->allowed_crud_action('delete')):?>
					<a data-text="<?=__('Are you sure you want to delete?')?>" 
						data-id="tr<?=$element->pk()?>" class="btn btn-danger index-delete" title="<?=__('Delete')?>" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete','id'=>$element->pk()))?>">
						<i class="icon-trash icon-white"></i>
					</a>
					<?endif?>

				</td>
				<?endif?>
			</tr>
		<?endforeach?>
	</tbody>
</table>

<?=$pagination?>