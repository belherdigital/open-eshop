<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1" title="<?=__('Check for updates')?>">
    <span class="glyphicon  glyphicon-refresh"></span> <?=__('Check for updates')?>
</a>

<div class="page-header">
    <h1><?=__('Updates')?></h1>
    <p>
        <?=__('Your installation version is')?> <span class="label label-info"><?=core::VERSION?></span>
    </p>
    <p>
        <?=__('Your Hash Key for this installation is')?> 
        <span class="label label-info"><?=core::config('auth.hash_key')?></span>
    </p>
    
    <?if ($latest_version!=core::VERSION):?>
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading"><?=__('Update')?></h4>
            <p>
                <?=__('You are not using latest version, please update.')?>
            </p>
        </div>
    <?endif?>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th><?=__('Version')?></th>
                <th><?=__('Name')?></th>
                <th><?=__('Release Date')?></th>
                <th><?=__('Changelog')?></th>
                <th><?=__('Release Notes')?></th>
            </tr>
            <?foreach ($versions as $version=>$values):?> 
                <tr>
                    <td>
                        <?=$version?>
                        <?=($version==$latest_version)? '<span class="label label-success">'.__('Latest').'</span>':''?>
                        <?=($version==core::VERSION)? '<span class="label label-info">'.__('Current').'</span>':''?>
                    </td>
                    <td>
                        <?=$values['codename']?>    
                    </td>
                    <td>
                        <?=$values['released']?>
                    </td>
                    <td>
                        <a target="_blank" href="<?=$values['changelog']?>"><?=__('Changelog')?> <?=$version?></a>
                    </td>
                    <td>
                        <a target="_blank" href="<?=$values['blog']?>"><?=__('Release Notes')?> <?=$version?></a>
                    </td>
                </tr>
            <?endforeach?>
        </table>
    </div>
</div>
