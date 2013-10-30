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

<div class="btn-group btn-primary pull-right">
    <button class="btn btn-primary"><?=__('Menu type')?></button>
    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" id="menu_type">
        <!-- dropdown menu links -->
        <li><a><?=__('Custom')?></a></li>
        <li><a><?=__('Categories')?></a></li>
        <li><a><?=__('Default')?></a></li>
    </ul>
</div>

<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'new'))?>">
<h2><?=__('Create Menu Item')?></h2>
<!-- drop down selector -->
<div class="control-group" style="display:none;" id="categories-group">
    <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' ))?>
    <div class="controls"> 
    <div class="accordion" >

    <?function lili3($item, $key,$cats){?>
        <div class="accordion-group">
            <div class="accordion-heading"> 

                <?if (count($item)>0):?>
                    <label class="radio">
                        <a class="btn btn-primary btn-mini" data-toggle="collapse" type="button"  
                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                            <i class=" icon-plus icon-white"></i> <?=$cats[$key]['name']?>
                        </a>
                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="category" value="<?=$cats[$key]['id']?>" required > 
                    
                     <?if ($cats[$key]['price']>0):?>
                        <span class="label label-success">
                        <?=i18n::money_format( $cats[$key]['price'])?>
                        </span>
                    <?endif?>
                    
                    </label>
                    
                <?else:?>
                    <label class="radio">
                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="category" value="<?=$cats[$key]['id']?>" required > 
                    
                        <a class="btn btn-mini btn-primary" data-toggle="collapse" type="button"  
                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                            <?=$cats[$key]['name']?>
                        </a>

                     <?if ($cats[$key]['price']>0):?>
                        <span class="label label-success">
                        <?=i18n::money_format( $cats[$key]['price'])?>
                        </span>
                    <?endif?>
                    </label>
                <?endif?>
            </div>

            <?if (count($item)>0):?>
                <div id="acc_<?=$cats[$key]['seoname']?>" 
                    class="accordion-body collapse <?=($cats[$key]['seoname']==Core::get('category'))?'in':''?>">
                    <div class="accordion-inner">
                        <? if (is_array($item)) array_walk($item, 'lili3', $cats);?>
                    </div>
                </div>
            <?endif?>

        </div>
    <?}array_walk($order_categories, 'lili3',$categories);?>

    </div>
    </div>
</div>
<!-- end dropdown categories -->

<div class="control-group" style="display:none;" id="default-group">
    <?= FORM::label('default_links_label', __('Default links'), array('class'=>'control-label', 'for'=>'default_links' ))?>
    <div class="controls">
        <select name="default_links" id="default_links" class="input-xlarge">
            <option></option>
            <option  data-url="" data-icon="icon-home icon-white"><?=__('Home')?></option>
            <option  data-url="all" data-icon="icon-list icon-white"><?=__('Listing')?></option>
            <option  data-url="search.html" data-icon="icon-search icon-white"><?=__('Search')?></option>
            <option  data-url="contact.html" data-icon="icon-envelope icon-white"><?=__('Contact')?></option>
            <option  data-url="rss.xml" data-icon="icon-signal icon-white"><?=__('RSS')?></option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?=__('Title')?></label>
    <div class="controls docs-input-sizes">
        <input class="input-xlarge" type="text" name="title" value="<?=Core::post('title')?>" placeholder="<?=__('Title')?>">
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?=__('Url')?></label>
    <div class="controls docs-input-sizes">
        <input class="input-xlarge" type="text" id="url" name="url" value="<?=Core::post('Url')?>" placeholder="http://somedomain.com">
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