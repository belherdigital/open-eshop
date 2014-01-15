<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Custom menu')?></h1>  
</div>

<div class="row">
<ol class='plholder col-md-9' id="ol_1" data-id="1">
<?if (is_array($menu)):?>
<?foreach($menu as $key=>$data):?>
    <li data-id="<?=$key?>" id="<?=$key?>"><i class="glyphicon glyphicon-move"></i> 
        <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i> <?endif?>
               
        <span class="label label-info "><?=$data['title']?></span>
        <?=$data['url']?> (<?=$data['target']?>)
        <a data-text="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
           data-id="li_<?=$key?>" 
           class="btn btn-mini btn-danger index-delete pull-right"  
           href="<?=Route::url('oc-panel', array('controller'=> 'menu', 'action'=>'delete','id'=>$key))?>">
                    <i class="glyphicon glyphicon-trash?v=2.1.2"></i>
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
        <li><a class="custom"><?=__('Custom')?></a></li>
        <li><a class="categories"><?=__('Categories')?></a></li>
        <li><a class="default"><?=__('Default')?></a></li>
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
                            <i class=" glyphicon glyphicon-plus?v=2.1.2"></i> <?=$cats[$key]['name']?>
                        </a>
                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" data-name="radio_<?=$cats[$key]['name']?>" class="menu_category"  value="<?=$cats[$key]['id']?>" required > 
                    </label>
                    
                <?else:?>
                    <label class="radio">
                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" data-name="radio_<?=$cats[$key]['name']?>" class="menu_category"  value="<?=$cats[$key]['id']?>" required > 
                    
                        <a class="btn btn-mini btn-primary" data-toggle="collapse" type="button"  
                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                            <?=$cats[$key]['name']?>
                        </a>
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

<div class="control-group"  id="default-group" style="display:none;">
    <?= FORM::label('default_links_label', __('Default links'), array('class'=>'control-label', 'for'=>'default_links' ))?>
    <div class="controls"> 
        <div class="accordion" >
            <div class="accordion-group">
                <div class="accordion-heading">
                <label class="radio">
                <input type="radio" class="default_links" id="radio_home"  name="home" data-url="" data-icon="glyphicon glyphicon-home?v=2.1.2" value="home">    
                    <a class="btn btn-primary btn-mini" type="button"  >                    
                         <?=__('Home')?>
                    </a>
                </label>
                <label class="radio">
                <input type="radio" class="default_links" id="radio_listing" name="listing" data-url="all" data-icon="glyphicon glyphicon-list?v=2.1.2" value="listing">
                    <a class="btn btn-primary btn-mini" type="button"  >                    
                         <?=__('listing')?>
                    </a>
                </label>
                <label class="radio">
                <input type="radio" class="default_links" id="radio_search" name="search" data-url="search.html" data-icon="glyphicon glyphicon-search?v=2.1.2" value="search">
                    <a class="btn btn-primary btn-mini" type="button"  >                    
                         <?=__('Search')?>
                    </a>
                </label>
                <label class="radio">
                <input type="radio" class="default_links" id="radio_contact" name="contact" data-url="contact.html" data-icon="glyphicon glyphicon-envelope?v=2.1.2" value="contact">
                    <a class="btn btn-primary btn-mini" type="button"  >                    
                         <?=__('contact')?>
                    </a>
                </label>
                <label class="radio">
                <input type="radio" class="default_links" id="radio_rss" name="rss" data-url="rss.xml" data-icon="glyphicon glyphicon-signal?v=2.1.2" value="rss">
                    <a class="btn btn-primary btn-mini" type="button"  >                    
                         <?=__('rss')?>
                    </a>
                </label>
                <label class="radio">
                <input type="radio" class="default_links" id="radio_map" name="map" data-url="map.html" data-icon="glyphicon glyphicon-globe?v=2.1.2" value="map">
                    <a class="btn btn-primary btn-mini" type="button"  >                    
                         <?=__('map')?>
                    </a>
                </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?=__('Title')?></label>
    <div class="controls docs-input-sizes">
        <input class="input-xlarge" type="text" name="title" value="<?=Core::post('title')?>" placeholder="<?=__('Title')?>" required>
    </div>
</div>

<div class="control-group">
    <label class="control-label"><?=__('Url')?></label>
    <div class="controls docs-input-sizes">
        <input class="input-xlarge" type="url" id="url" name="url" value="<?=Core::post('Url')?>" placeholder="http://somedomain.com" required>
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
        <input class="input-xlarge" type="text" name="icon" value="<?=Core::post('icon')?>" placeholder="<?=__('glyphicon glyphicon-envelope?v=2.1.2')?>">
    </div>
</div>

<div class="form-actions">

<button type="submit" class="btn btn-primary"><?=__('Save')?></button>
</div>
          

</form>