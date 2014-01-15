<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('System Logs')?></h1>
    <p><?=__('Reading log file')?><code> <?=$file?></code></p>
    <form id="" class="form-inline" method="get" action="">
        <fieldset>
            <input  type="text" class="col-md-2" size="16" id="date" name="date"  value="<?=$date?>" data-date-format="yyyy-mm-dd">
            <button class="btn btn-primary"><?=__('Log')?></button>
        </fieldset>
    </form>
</div>

<textarea class="col-md-9" rows="20">
<?=$log?>
</textarea>