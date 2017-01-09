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
            <?=__('System Logs')?>
        </h1>
        <hr>
        <p><?=__('Reading log file')?><code> <?=$file?></code></p>
                <form id="" class="form-inline" method="get" action="">
                    <fieldset>
                        <div class="form-group">
                            <div class="input-group">
                                <input  type="text" class="form-control" size="16" id="date" name="date"  value="<?=$date?>" data-date-format="yyyy-mm-dd">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-primary"><?=__('Log')?></button>
                    </fieldset>
                </form>
                <br>
                <textarea class="col-md-9 form-control" rows="20">
                    <?=$log?>
                </textarea>
    </div>
</div>