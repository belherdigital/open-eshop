<?php defined('SYSPATH') or die('No direct script access.');?>


<h1 class="page-header page-title"><?=__('Market')?></h1>
<hr>
    <p><?=__('Selection of nice extras for your installation.')?></p>


<div class="row">
    <?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>
</div>