<?php defined('SYSPATH') or die('No direct script access.');?>
<?if ($controller->allowed_crud_action('create')):?>
    <ul class="list-inline pull-right">
        <li>
            <a class="btn btn-primary ajax-load btn-icon-left" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" title="<?=__('New')?>">
                <i class="fa fa-plus-circle"></i><?=__('New')?>
            </a>
        </li>
    </ul>
<?endif?>

<h1 class="page-header page-title">
    <?=Text::ucfirst(__($name))?>
    <?if($name == 'product'):?>
        <small><a href="https://docs.open-eshop.com/add-product/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?elseif($name == 'license'):?>
        <p><a href="https://docs.open-eshop.com/manage-licenses/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?elseif($name == 'user'):?>
        <small><a href="https://docs.yclas.com/manage-users/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?elseif($name == 'role'):?>
        <small><a href="https://docs.yclas.com/roles-work-classified-ads-script/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?elseif($name == 'order'):?>
        <small><a href="https://docs.yclas.com/how-to-manage-orders/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?elseif($name == 'crontab'):?>
        <small><a href="https://docs.yclas.com/how-to-set-crons/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?elseif($name == 'plan'):?>
        <small><a href="https://docs.yclas.com/membership-plans/" target="_blank"><i class="fa fa-question-circle"></i></a></small>
    <?endif?>
</h1>

<hr>

<?if($extra_info_view):?>
    <p><?=$extra_info_view?></p>
<?endif?>

<div id="filter_buttons" data-url="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'ajax')).'?'.str_replace('rel=ajax','',$_SERVER['QUERY_STRING']) ?>">
    <?if (count($filters)>0):?>
        <form class="form-inline form-hidden-elements" id="form-ajax-load" method="get" action="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'index')) ?>">
            <?foreach($filters as $field_name=>$values):?>
                <?if (is_array($values)):?>
                    <select name="filter__<?=$field_name?>" id="filter__<?=$field_name?>" class="form-control disable-chosen disable-select2" >
                        <option value=""><?=__('Select')?> <?=$field_name?></option>
                        <?foreach ($values as $key=>$value):?>
                            <option value="<?=$key?>" <?=(core::request('filter__'.$field_name)==$key AND core::request('filter__'.$field_name)!==NULL)?'SELECTED':''?> >
                                <?=$field_name?> = <?=$value?>
                            </option>
                        <?endforeach?>
                    </select>
                <?elseif($values=='DATE'):?>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('From')?> <?=$field_name?></div>
                            <input type="text" class="form-control datepicker_boot" id="filter__from__<?=$field_name?>" name="filter__from__<?=$field_name?>" value="<?=core::request('filter__from__'.$field_name)?>" data-date="<?=core::request('filter__from__'.$field_name)?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('To')?> <?=$field_name?></div>
                            <input type="text" class="form-control datepicker_boot" id="filter__to__<?=$field_name?>" name="filter__to__<?=$field_name?>" value="<?=core::request('filter__to__'.$field_name)?>" data-date="<?=core::request('filter__to__'.$field_name)?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                <?elseif($values=='RANGE'):?>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter__from__<?=$field_name?>" name="filter__from__<?=$field_name?>" placeholder="<?=__('From')?> <?=$field_name?>" value="<?=core::request('filter__from__'.$field_name)?>" >
                        </div>
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter__to__<?=$field_name?>" name="filter__to__<?=$field_name?>" placeholder="<?=__('To')?> <?=$field_name?>" value="<?=core::request('filter__to__'.$field_name)?>" >
                        </div>
                    </div>
                       
                <?elseif($values=='INPUT'):?>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="filter__<?=$field_name?>" name="filter__<?=$field_name?>" placeholder="<?=(isset($captions[$field_name])?$captions[$field_name]['model'].' '.$captions[$field_name]['caption']:$field_name)?>" value="<?=core::request('filter__'.$field_name)?>" >
                        </div>
                    </div>
                <?endif?>
            <?endforeach?>
            <button type="submit" class="btn btn-primary btn-icon-left"><?=__('Filter')?></button>
        </form>
    <?endif?>
</div>

<div class="clearfix"></div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table id="grid-data-api" class="table table-striped table-responsive">
            <thead>
                <tr>
                    <?foreach($fields as $field):?>
                        <th data-column-id="<?=$field?>" <?=($elements->primary_key() == $field)?'data-identifier="true"':''?> >
                            <?if(isset($captions[$field]) AND !exec::is_callable($captions[$field])):?>
                                <?=Text::ucfirst($captions[$field]['model'].' '.$captions[$field]['caption'])?>
                            <?else:?>
                                <?=Text::ucfirst($field)?>
                            <?endif?>
                        </th>
                    <?endforeach?>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">
                        <?=__('Actions')?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>

    <?if ($controller->allowed_crud_action('export')):?>
        <div class="panel-footer">
            <p class="text-right">
                <a class="btn btn-success" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
                    <i class="glyphicon glyphicon-download"></i>
                    <?=__('Export all')?>
                </a>
            </p>
        </div>            
    <?endif?>
</div>