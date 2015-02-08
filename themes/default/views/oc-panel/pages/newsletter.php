<?php defined('SYSPATH') or die('No direct script access.');?>

<?=View::factory('oc-panel/elasticemail')?>

<?if (Theme::get('premium')!=1):?>
    <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
        <?=__('Only if you have a premium theme you will be able to filter by users!').'<br/>'.__('Upgrade your Open eShop site to activate this feature.')?>
        <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
    </p>
<?endif?>

<div class="page-header">
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'email'))?>?force=1">
        <?=__('Email Settings')?>
    </a>
	<h1><?=__('Newsletter')?></h1>
    <a href="http://open-classifieds.com/2013/08/23/how-to-send-the-newsletter/" target="_blank"><?=__('Read more')?></a>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'newsletter','action'=>'index'))?>">  
            
                <?=Form::errors()?> 
                <div class="form-group">
                    <label class="col-md-2 control-label"><?=__('To')?>:</label>
                    <div class="col-md-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="send_all" checked >
                                <?=__('All active users.')?> <span class="badge badge-info"><?=$count_all_users?></span>
                            </label>
                        </div> 
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="send_expired_support"  >
                                <?=__('Expired support.')?> <span class="badge badge-info"><?=$count_support_expired?></span>
                            </label>
                        </div> 
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="send_expired_license"  >
                                <?=__('Expired license.')?> <span class="badge badge-info"><?=$count_license_expired?></span>
                            </label>
                        </div> 
                        <select name="send_product" class="form-control">
                            <option><?=__('Users who bought the product')?></option>
                            <?foreach ($products as $p):?>
                                <option value="<?=$p['id_product']?>" <?=(core::request('send_product')==$p['id_product'])?'selected':''?>>
                                    <?=$p['title']?> (<?=$p['count']?>)
                                </option>
                            <?endforeach?>
                        </select>
                    </div> 
                </div>
               
                <div class="form-group">
                    <label class="col-md-2 control-label"><?=__('From')?>:</label>
                    <div class="col-md-10">
                        <input type="text" name="from" value="<?=Auth::instance()->get_user()->name?>" class="col-md-6 form-control">
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-md-2 control-label"><?=__('From Email')?>:</label>
                    <div class="col-md-10">
                        <input  type="text" name="from_email" value="<?=Auth::instance()->get_user()->email?>" class="col-md-6 form-control">
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-md-2 control-label"><?=__('Subject')?>:</label>
                    <div class="col-md-10">
                        <input  type="text" name="subject" value="" class="col-md-6 form-control">
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-md-2 control-label"><?=__('Message')?>:</label>
                    <div class="col-md-10">
                        <textarea  name="description"  id="formorm_description" class="col-md-10 col-sm-10 col-xs-12 form-control" data-editor="html" rows="15" ></textarea>
                    </div>
                </div>
                  
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
                        <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
                    </div>
                </div>
        </form>
    </div>
</div>    