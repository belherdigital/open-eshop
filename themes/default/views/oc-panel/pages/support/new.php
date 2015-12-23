<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('New Ticket')?></h1>
    <p><?=__('Please be as specific as possible. Indicate software version, your URL and any other relevant information to help you.')?>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'new'))?>">
                    
                    <?php if ($errors): ?>
                        <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
                        <ul class="errors">
                            <?php foreach ($errors as $message): ?>
                                <li><?php echo $message ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?> 
                  
                    <div class="form-group">
                        <label class="col-md-2"><?=__("Product")?>:</label>
                        <div class="col-md-5">
                        <select name="order" class="col-md-4 form-control" required>
                            <option></option>
                            <?foreach ($orders as $order):?>
                                <option  value="<?=$order->id_order?>" <?=(core::post('order')==$order->id_order)?'SELECTED':''?>>
                                    <?=$order->product->title?> - <?=substr($order->support_date,0,10)?>
                                </option>
                            <?endforeach?>
                        </select>
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-md-2"><?=__("Title")?>:</label>
                        <div class="col-md-5">
                        <input  type="text" name="title" value="<?=core::post('title')?>" class="col-md-6 form-control"  required /> 
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-md-2"><?=__("Description")?>:</label>
                        <div class="col-md-9 col-sm-9 col-md-12">
                        <textarea id="description" name="description" rows="20" class="col-md-9 col-sm-9 col-md-12 form-control" required><?=core::post('description',__('Description'))?></textarea>
                        </div>
                    </div>
                  
                    <div class="form-actions">
                          <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index'))?>" class="btn btn-default"><?=__('Cancel')?></a>
                        <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
                    </div>
                
                </form>
            </div>
        </div>
    </div>
</div>