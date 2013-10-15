<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
		<h1><?=__('Migration')?></h1>
    <p><?=__("Your PHP time limit is")?> <?=ini_get('max_execution_time')?> <?=__("seconds")?></p>
	</div>
	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'migration'))?>">         
          <?=Form::errors()?>        
    

      <div class="control-group">
        <label class="control-label"><?=__("Host name")?>:</label>
        <div class="controls">
        <input  type="text" name="hostname" value="<?=$db_config['connection']['hostname']?>" class="span3"  />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("User name")?>:</label>
        <div class="controls">
        <input  type="text" name="username"  value="<?=$db_config['connection']['username']?>" class="span3"   />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Password")?>:</label>
        <div class="controls">
        <input type="text" name="password" value="" class="span3" />   
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Database name")?>:</label>
        <div class="controls">
        <input type="text" name="database" value="<?=$db_config['connection']['database']?>"  class="span3"  />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Database charset")?>:</label>
        <div class="controls">
        <input type="text" name="charset" value="<?=$db_config['charset']?>"  class="span3"   />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Table prefix")?>:</label>
        <div class="controls">
        <input type="text" name="table_prefix" value="oc_" class="span3" />
        </div>
      </div>

          
          
      <div class="form-actions">
      	<a href="<?=Route::url('oc-panel')?>" class="btn"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Migrate')?></button>
      </div>
	</form>    