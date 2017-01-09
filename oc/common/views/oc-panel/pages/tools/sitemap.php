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
                <a class="btn btn-primary ajax-load" title="<?=__('Sitemap')?>" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>?force=1">
                    <?=__('Generate')?>
                </a>
            </li>
        </ul>
        <h1 class="page-header page-title">
            <?=__('Sitemap')?>
            <a target="_blank" href="https://docs.yclas.com/sitemap-classifieds-website/">
                <i class="fa fa-question-circle"></i>
            </a>
        </h1>
        <hr>
        <ul class="list-unstyled">
            <li><?=__('Last time generated')?> <?=Date::unix2mysql(Sitemap::last_generated_time())?></li>
            <li><?=__('Your sitemap XML to submit to engines')?></li>
            <li><input type="text" value="<?=core::config('general.base_url')?><?=(file_exists(DOCROOT.'sitemap-index.xml'))? 'sitemap-index.xml':'sitemap.xml.gz'?>" /></li>
        </ul>
    </div>
</div>