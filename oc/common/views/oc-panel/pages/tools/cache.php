<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="nav nav-tabs nav-tabs-simple">
    <li <?=(Request::current()->action()=='optimize') ? 'class="active"' : NULL?>>
        <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'optimize'))?>" 
            title="<?=HTML::chars(__('Optimize'))?>" 
            class="ajax-load">
            <?=__('Optimize')?>
        </a>
    </li>
    <li <?=(Request::current()->action()=='sitemap') ? 'class="active"' : NULL?>>
        <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>" 
            title="<?=HTML::chars(__('Sitemap'))?>" 
            class="ajax-load">
            <?=__('Sitemap')?>
        </a>
    </li>
    <li <?=(Request::current()->action()=='migration') ? 'class="active"' : NULL?>>
        <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'migration'))?>" 
            title="<?=HTML::chars(__('Migration'))?>" 
            class="ajax-load">
            <?=__('Migration')?>
        </a>
    </li>
    <li <?=(Request::current()->action()=='cache') ? 'class="active"' : NULL?>>
        <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>" 
            title="<?=HTML::chars(__('Cache'))?>" 
            class="ajax-load">
            <?=__('Cache')?>
        </a>
    </li>
    <li <?=(Request::current()->action()=='logs') ? 'class="active"' : NULL?>>
        <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'logs'))?>" 
            title="<?=HTML::chars(__('Logs'))?>" 
            class="ajax-load">
            <?=__('Logs')?>
        </a>
    </li>
    <li <?=(Request::current()->action()=='phpinfo') ? 'class="active"' : NULL?>>
        <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'phpinfo'))?>" 
            title="<?=HTML::chars(__('PHP Info'))?>" 
            class="ajax-load">
            <?=__('PHP Info')?>
        </a>
    </li>
</ul>

<div class="panel panel-default">
    <div class="panel-body">
        <ul class="list-inline pull-right">
            <li>
                <a class="btn btn-warning pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=1" title="<?=__('Delete all')?>">
                    <?=__('Delete all')?>
                </a>
            </li>
            <li>
                <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=2" title="<?=__('Delete expired')?>">
                    <?=__('Delete expired')?>
                </a>
            </li>
        </ul>
        <h1 class="page-header page-title">
            <?=__('Cache')?>
            <a target="_blank" href="https://docs.yclas.com/modify-cache-time/">
                <i class="fa fa-question-circle"></i>
            </a>
        </h1>
        <hr>
        <div class="panel panel-default">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=__('Config file')?></th>
                        <th><?=APPPATH?>config/cache.php</th>
                    </tr>
                </thead>
                <tbody>
                    <?foreach ($cache_config as $key => $value):?>
                        <tr>
                            <td><?=$key?></td>
                            <td><?=print_r($value,1)?></td>
                        </tr>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>