<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="pull-right" action="<?=Route::URL('forum-home')?>" method="get">
    <div class="pull-right">&nbsp;</div>
    <button class="btn btn-default pull-right" type="submit" value="<?=__('Search')?>"><?=_e('Search')?></button>
    <div class="pull-right">&nbsp;</div>
    <div class="pull-right">
        <input type="text" class="form-control" id="task-table-filter" data-action="filter" data-filters="#task-table" placeholder="<?=__('Search')?>" type="search" value="<?=core::get('search')?>" name="search" />
    </div>
</form>