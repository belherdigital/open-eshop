<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
    <h1><?=__('New')?> <?=ucfirst(__($name))?></h1>
</div>
<?=$form->render()?>