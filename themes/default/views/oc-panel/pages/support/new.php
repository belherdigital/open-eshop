<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
		<h1><?=__('New Ticket')?></h1>
        <p><?=__('Please be as specific as possible. Indicate software version, your URL and any other relevant information to help you.')?>
	</div>

	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'new'))?>">         
      
      <?php if ($errors): ?>
        <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
        <ul class="errors">
        <?php foreach ($errors as $message): ?>
            <li><?php echo $message ?></li>
        <?php endforeach ?>
        </ul>
        <?php endif ?>       

      <div class="control-group">
        <label class="control-label"><?=__("Product")?>:</label>
        <div class="controls">
        <select name="order" class="span4" required>
            <option></option>
            <?foreach ($orders as $order):?>
                <option  value="<?=$order->id_order?>" <?=(core::post('order')==$order->id_order)?'SELECTED':''?>>
                    <?=$order->product->title?> - <?=substr($order->support_date,0,10)?>
                </option>
            <?endforeach?>
        </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Title")?>:</label>
        <div class="controls">
        <input  type="text" name="title" value="<?=core::post('title')?>" class="span6"  required /> 
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Description")?>:</label>
        <div class="controls">
        <textarea name="description" rows="20" class="span6" required><?=core::post('description')?></textarea>
        </div>
      </div>

      
        
          
      <div class="form-actions">
      	<a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index'))?>" class="btn"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
      </div>
	</form>    