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
                <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'optimize'))?>?force=1" title="<?=__('Optimize')?>">
                    <?=__('Optimize')?>
                </a>
            </li>
        </ul>
        <h1 class="page-header page-title">
            <?=__('Optimize Database')?>
        </h1>
        <hr>
        <ul class="list-unstyled">
            <li><?=__('Database space')?> <?=round($total_space,2)?> KB</li>
            <li><?=__('Space to optimize')?> <?=round($total_gain,2)?> KB</li>
        </ul>
        <div class="panel panel-default">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=__('Table')?></th>
                        <th><?=__('Rows')?></th>
                        <th><?=__('Size')?> KB</th>
                        <th><?=__('Save size')?> KB</th>
                    </tr>
                </thead>
        
                <tbody>
                    <?foreach ($tables as $table):?>
                        <tr class="<?=($table['gain']>0)?'warning':''?>">
                            <td><?=$table['name']?></td>
                            <td><?=$table['rows']?></td>
                            <td><?=$table['space']?></td>
                            <td><?=$table['gain']?></td>
                        </tr>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>