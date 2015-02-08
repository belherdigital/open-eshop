<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
    <h1><?=__('New')?> <?=ucfirst(__($name))?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
    </div>
</div>