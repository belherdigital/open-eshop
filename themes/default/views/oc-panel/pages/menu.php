<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Custom menu')?></h1>  
</div>

<div class="row">
<ol class='plholder span9' id="ol_1" data-id="1">
<?if (is_array($menu)):?>
<?foreach($menu as $key=>$data):?>
    <li data-id="<?=$key?>" id="<?=$key?>"><i class="icon-move"></i> 
        <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i> <?endif?>
               
        <span class="label label-info "><?=$data['title']?></span>
        <?=$data['url']?> (<?=$data['target']?>)
        <a data-text="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
           data-id="li_<?=$key?>" 
           class="btn btn-mini btn-danger index-delete pull-right"  
           href="<?=Route::url('oc-panel', array('controller'=> 'menu', 'action'=>'delete','id'=>$key))?>">
                    <i class="icon-trash icon-white"></i>
        </a>
    </li>
<?endforeach?>
<?endif?>
</ol><!--ol_1-->

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'saveorder'))?>'></span>
</div>

<hr>
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'new'))?>">
<h2><?=__('Create Menu Item')?></h2>
<div class="control-group">
            <label class="control-label"><?=__('Title')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-xlarge" type="text" name="title" value="<?=Core::post('title')?>" placeholder="<?=__('Title')?>">
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label"><?=__('Url')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-xlarge" type="text" name="url" value="<?=Core::post('Url')?>" placeholder="http://somedomain.com">
            </div>
          </div>

          <div class="control-group">
                <?= FORM::label('target', __('Target'), array('class'=>'control-label', 'for'=>'target' ))?>
                <div class="controls">
                    <select name="target" id="target" class="input-xlarge" REQUIRED>
                        <option>_self</option>
                        <option>_blank</option>
                        <option>_parent</option>
                        <option>_top</option>
                    </select>
                </div>
            </div>

          <div class="control-group">
            <label class="control-label"><a target="_blank" href="http://getbootstrap.com/2.3.2/base-css.html#icons"><?=__('Icon')?></a></label>
            <div class="controls docs-input-sizes">
              <input class="input-xlarge" type="text" name="icon" value="<?=Core::post('icon')?>" placeholder="<?=__('icon-envelope icon-white')?>">
            </div>
          </div>
          
          <div class="form-actions">
            
            <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
          </div>
          

</form>