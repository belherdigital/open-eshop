<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Custom Fields')?></h1>


    <?if (Theme::get('premium')!=1):?>
        <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
            <?=__('Custom fields are only available with premium themes!').'<br/>'.__('Upgrade your Open eShop site to activate this feature.')?>
            <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
        </p>
    <?endif?>

    <a target='_blank' href='http://open-classifieds.com/2013/10/11/how-to-create-custom-fields/'><p><?=__('AdvertisementS Custom Fields')?></p></a>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'new'))?>">
  <?=__('New field')?></a>
</div>


<ol class='plholder span6' id="ol_1" data-id="1">
<?if (is_array($fields)):?>
<?foreach($fields as $name=>$field):?>
    <li data-id="<?=$name?>" id="<?=$name?>"><i class="icon-move"></i> 
        <?=$name?>        
        <span class="label label-info "><?=$field['type']?></span>

        <a data-text="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
           data-id="li_<?=$name?>" 
           class="btn btn-mini btn-danger index-delete pull-right"  
           href="<?=Route::url('oc-panel', array('controller'=> 'fields', 'action'=>'delete','id'=>$name))?>">
                    <i class="icon-trash icon-white"></i>
        </a>

        <a class="btn btn-mini btn-primary pull-right" 
            href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'update','id'=>$name))?>">
            <?=__('Edit')?>
        </a>
    </li>
<?endforeach?>
<?endif?>
</ol><!--ol_1-->

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'saveorder'))?>'></span>


<div class="page-header pull-left">
    <hr>
  <h1><?=__('Optional Fields')?></h1>
    <p><?=__('Optional Advertisement Fields')?></a></p>

    <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')).'?define=cf', array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
      <fieldset>
        <div class="control-group">
          <?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
          <div class="controls">
            <?= FORM::select('phone', array(FALSE=>"FALSE",TRUE=>"TRUE"),core::config('advertisement.phone'), array(
            'placeholder' => "", 
            'class' => 'tips', 
            'id' => 'phone', 
            'data-content'=> __("Phone field"),
            'data-trigger'=>"hover",
            'data-placement'=>"right",
            'data-toggle'=>"popover",
            'data-original-title'=>__("Displays the field Phone in the Ad form."),
            ))?> 
          </div>
        </div>
        <div class="control-group">
        <?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
        <div class="controls">
            <?= FORM::select('website', array(FALSE=>"FALSE",TRUE=>"TRUE"),core::config('advertisement.website'), array(
            'placeholder' => "http://foo.com/", 
            'class' => 'tips', 
            'id' => 'website', 
            'data-content'=> __("Website field"),
            'data-trigger'=>"hover",
            'data-placement'=>"right",
            'data-toggle'=>"popover",
            'data-original-title'=>__("Displays the field Website in the Ad form."),
            ))?> 
          </div>
        </div>
        <div class="control-group">
          <?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
          <div class="controls">
            <?= FORM::select('location',array(FALSE=>"FALSE",TRUE=>"TRUE"), core::config('advertisement.location'), array(
            'placeholder' => "", 
            'class' => 'tips', 
            'id' => 'location', 
            'data-content'=> __("Displays location select"),
            'data-trigger'=>"hover",
            'data-placement'=>"right",
            'data-toggle'=>"popover",
            'data-original-title'=>__("Displays the Select Location in the Ad form."),
            ))?> 
          </div>
        </div>
        <div class="control-group">
          <?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
          <div class="controls">
            <?= FORM::select('price', array(FALSE=>"FALSE",TRUE=>"TRUE"),core::config('advertisement.price'), array(
            'placeholder' => "", 
            'class' => 'tips', 
            'id' => 'price', 
            'data-content'=> __("Price field"),
            'data-trigger'=>"hover",
            'data-placement'=>"right",
            'data-toggle'=>"popover",
            'data-original-title'=>__("Displays the field Price in the Ad form."),
            ))?> 
          </div>
        </div>
        <div class="control-group">
          <?= FORM::label('upload_file', __('Upload file'), array('class'=>'control-label', 'for'=>'upload_file'))?>
          <div class="controls">
            <?= FORM::select('upload_file',array(FALSE=>"FALSE",TRUE=>"TRUE"), core::config('advertisement.upload_file'), array(
            'placeholder' => "", 
            'class' => 'tips', 
            'id' => 'upload_file', 
            ))?>
          </div>
        </div>
        <div class="control-group">
          <?= FORM::label('captcha', __('Captcha'), array('class'=>'control-label', 'for'=>'captcha'))?>
          <div class="controls">
            <?= FORM::select('captcha', array(FALSE=>"FALSE",TRUE=>"TRUE"), core::config('advertisement.captcha'), array(
            'placeholder' => "http://foo.com/", 
            'class' => 'tips', 
            'id' => 'captcha', 
            'data-content'=> __("Enables Captcha"),
            'data-trigger'=>"hover",
            'data-placement'=>"right",
            'data-toggle'=>"popover",
            'data-original-title'=>__("Captcha appears in the form."),
            ))?> 
          </div>
        </div>
        <div class="control-group">
          <?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
          <div class="controls">
            <?= FORM::select('address', array(FALSE=>"FALSE",TRUE=>"TRUE"),core::config('advertisement.address'), array(
            'placeholder' => "", 
            'class' => 'tips', 
            'id' => 'address', 
            'data-content'=> __("Address field"),
            'data-trigger'=>"hover",
            'data-placement'=>"right",
            'data-toggle'=>"popover",
            'data-original-title'=>__("Displays the field Address in the Ad form."),
            ))?> 
          </div>
        </div>
        <div class="form-actions">
          <?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')).'?define=cf'))?>
        </div>
      </fieldset>
    <?FORM::close()?>
</div>