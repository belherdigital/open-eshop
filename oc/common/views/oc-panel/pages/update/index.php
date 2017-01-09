<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($latest_version!=core::VERSION):?>
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading"><?=__('Update')?></h4>
        <p>
            <?=__('You are not using latest version, please update.')?>
        </p>

        <a class="btn btn-warning ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'confirm'))?>" title="<?=__('Update')?>">
            <span class="glyphicon  glyphicon-refresh"></span> <?=__('Update')?>
        </a>
    </div>
<?endif?>

<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-primary ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1" title="<?=__('Check for updates')?>">
            <span class="glyphicon  glyphicon-refresh"></span> <?=__('Check for updates')?>
        </a>
    </li>
</ul>

<h1 class="page-header page-title"><?=__('Updates')?></h1>

<hr>

<p>
    <?=__('Your installation version is')?> <span class="label label-info"><?=core::VERSION?></span>
</p>
<p>
    <?=__('Your Hash Key for this installation is')?> <span class="label label-info"><?=core::config('auth.hash_key')?></span>
</p>

<div class="panel panel-default">
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th class="sorting_disabled"><?=__('Version')?></th>
                <th class="sorting_disabled hidden-xs"><?=__('Name')?></th>
                <th class="sorting_disabled hidden-xs"><?=__('Release Date')?></th>
                <th class="sorting_disabled hidden-sm hidden-xs"><?=__('Changelog')?></th>
                <th class="sorting_disabled hidden-sm hidden-xs"><?=__('Release Notes')?></th>
            </tr>
        </thead>
        <tbody>
            <?foreach ($versions as $version=>$values):?> 
                <tr>
                    <td>
                        <?=$version?>
                        <?=($version==$latest_version)? '<span class="label label-success">'.__('Latest').'</span>':''?>
                        <?=($version==core::VERSION)? '<span class="label label-info">'.__('Current').'</span>':''?>
                    </td>
                    <td class="hidden-xs">
                        <?=$values['codename']?>    
                    </td>
                    <td class="hidden-xs">
                        <?=$values['released']?>
                    </td>
                    <td class="hidden-sm hidden-xs">
                        <a target="_blank" href="<?=$values['changelog']?>"><?=__('Changelog')?></a>
                    </td>
                    <td class="hidden-sm hidden-xs">
                        <a target="_blank" href="<?=$values['blog']?>"><?=__('Release Notes')?></a>
                    </td>
                </tr>
            <?endforeach?>
        </tbody>
    </table>
</div>