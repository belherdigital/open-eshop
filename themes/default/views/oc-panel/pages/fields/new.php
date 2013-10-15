<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('New Custom Field')?></h1>
</div>
        
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'new'))?>">         
      <?=Form::errors()?>  
      
        <div class="control-group">
            <label class="control-label"><?=__('Name')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-xlarge" type="text" name="name" placeholder="<?=__('Name')?>" required>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label"><?=__('Label')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-xlarge" type="text" name="label" placeholder="<?=__('Label')?>" required>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="date"><?=__('Type')?></label>            
            <div class="controls">          
                <select name="type"  class="input-xlarge" id="cf_type_fileds" required>
                    <option value="string"><?=__('Text 256 Chars')?></option>
                    <option value="textarea"><?=__('Text Long')?></option>
                    <option value="integer"><?=__('Number')?></option>  
                    <option value="decimal"><?=__('Number Decimal')?></option>
                    <option value="date"><?=__('Date')?></option>
                    <option value="select"><?=__('Select')?></option>
                    <option value="radio"><?=__('Radio')?></option>
                    <option value="checkbox"><?=__('Checkbox')?></option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?=__('Values')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-xlarge" id="cf_values_input" type="text" name="values" placeholder="<?=__('Comma separated for select')?>">
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                  <input type="checkbox" name="required"> 
                   <?=__('Required')?>
                </label>
            <div class="help-block"></div></div>
        </div>

        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                  <input type="checkbox" name="searchable"> 
                   <?=__('Searchable')?>
                </label>
            <div class="help-block"></div></div>
        </div>
      
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'index'))?>" class="btn"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
      </div>
</form>
