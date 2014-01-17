<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('System Logs')?></h1>
    <p><?=__('Reading log file')?><code> <?=$file?></code></p>
    <form id="" class="form-inline" method="get" action="">
        <fieldset>
        <div class="form-group">
            <input  type="text" class="form-control" size="16" id="date" name="date"  value="<?=$date?>" data-date-format="yyyy-mm-dd">
        </div>    
            <button class="btn btn-primary"><?=__('Log')?></button>
        </fieldset>
    </form>
</div>
<div class="form-group">
	<textarea class="form-control" rows="20">
	<?=$log?>
	</textarea>
</div>