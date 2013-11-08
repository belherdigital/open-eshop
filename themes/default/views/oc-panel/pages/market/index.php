<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Market')?></h1>
    <p><?=__('Selection of nice extras for your installation.')?></p>
</div>

<?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    