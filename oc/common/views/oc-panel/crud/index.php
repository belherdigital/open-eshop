<?php defined('SYSPATH') or die('No direct script access.');?>

	<?if ($controller->allowed_crud_action('create')):?>
	<a class="btn btn-primary pull-right ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" title="<?=__('New')?>">
	<i class="fa fa-plus-circle"></i>&nbsp; <?=__('New')?>
	</a>				
	<?endif?>

<h1 class="page-header page-title"><?=Text::ucfirst(__($name))?></h1>
<hr>
	<?if($name == 'role'):?><p><a href="https://docs.yclas.com/roles-work-classified-ads-script/" target="_blank"><?=__('Read more')?></a></p><?endif;?>


<?if($name == "user") :?>
	<form class="form-horizontal" role="form" method="get" action="<?=URL::current();?>">
		<div class="form-group has-feedback">
			<label class="sr-only" for="search"><?=__('Search')?></label>
			<div class="col-md-4 col-md-offset-8">
				<input type="text" class="form-control search-query" name="search" placeholder="<?=__('Search users by name or email')?>" value="<?=core::get('search')?>">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</div>
	</form>
<?endif?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<?foreach($fields as $field):?>
							<th><?=Text::ucfirst((method_exists($orm = ORM::Factory($name), 'formo') ? Arr::path($orm->formo(), $field.'.label', __($field)) : __($field)))?></th>
						<?endforeach?>
						<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
						<th><?=__('Actions') ?></th>
						<?endif?>
					</tr>
				</thead>
				<tbody>
					<?foreach($elements as $element):?>
						<tr id="tr<?=$element->pk()?>">
							<?foreach($fields as $field):?>
								<td><?=HTML::chars($element->$field)?></td>
							<?endforeach?>
							<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
							<td width="80" style="width:80px;">
								<?if ($controller->allowed_crud_action('update')):?>
								<a title="<?=__('Edit')?>" class="btn btn-primary ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$element->pk()))?>">
									<i class="glyphicon   glyphicon-edit"></i>
								</a>
								<?endif?>
								<?if ($controller->allowed_crud_action('delete')):?>
								<a 
									href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete','id'=>$element->pk()))?>" 
									class="btn btn-danger index-delete" 
									title="<?=__('Are you sure you want to delete?')?>" 
									data-id="tr<?=$element->pk()?>" 
									data-btnOkLabel="<?=__('Yes, definitely!')?>" 
									data-btnCancelLabel="<?=__('No way!')?>">
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


<?if ($controller->allowed_crud_action('export')):?>
<a class="btn btn-sm btn-success pull-right " href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
    <i class="glyphicon glyphicon-download"></i>
    <?=__('Export all')?>
</a>                
<?endif?>