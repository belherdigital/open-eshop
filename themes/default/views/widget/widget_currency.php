<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (strtolower(Request::current()->controller()) == 'product' AND strtolower(Request::current()->action()) == 'view'):?>
	<?if ($widget->currency_title!=''):?>
	    <div class="panel-heading">
	        <h3 class="panel-title"><?=$widget->currency_title?></h3>
	    </div>
	<?endif?>
	<div class="panel-body">
	    <div class="btn-group curry" data-currencies="<?=($widget->currencies);?>">
	        <div class="my-future-ddm"></div>
	    </div>
	</div>
<?endif?>
