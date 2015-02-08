<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('Import Orders')?></h1>
    <h2><?=__('Mass import CSV file.')?></h2>
    <p><?=__('CSV file, first line with field names.')?> email;Date DD/MM/YYYY; SEOTITLE; 45.5; USD
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <form action="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'import'))?>" method="post" enctype="multipart/form-data">
            <input type="file" name="file_source" id="file_source">
            <input type="submit" class="btn btn-primary" name="import" value="Import it" onclick=" var s = document.getElementById('file_source'); if(null != s && '' == s.value) {alert('Define file name'); s.focus(); return false;}">
        </form>
    </div>
</div>