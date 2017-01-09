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
        <h1 class="page-header page-title">
            <?=__('PHP Info')?>
        </h1>
        <hr>
        <div class="panel panel-default">
            <div class="panel-body">
            	<?=$phpinfo?>
            </div>
        </div>
    </div>
</div>