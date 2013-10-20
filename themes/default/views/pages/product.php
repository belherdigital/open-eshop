<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$product->title?></h1>
</div>

<div class="well">
	<?=Text::bb2html($product->description,TRUE)?>
</div><!-- /well -->

<a href="<?=Route::url('default', array('controller'=>'paypal','action'=>'pay','id'=>$product->seotitle))?>"><?=__('Paypal')?></a>
<?=Paymill::button($product)?>
