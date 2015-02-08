<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
    <h1><?=__('Update')?> <?=ucfirst(__($name))?></h1>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="page-header">
                    <h3 class="panel-title"><?=__('Upload category icon')?></h3>
                </div>
                
                <?if (( $icon_src = $category->get_icon() )!==FALSE ):?>
                <div class="row">
                    <div class="col-md-3">
                        <a class="thumbnail">
                            <img src="<?=$icon_src?>" class="img-rounded" alt="<?=__('Category icon')?>" height="200" style="height:200px;">
                        </a>
                    </div>
                </div>
                <?endif?>
                <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'icon','id'=>$form->object->id_category))?>">         
                    <?=Form::errors()?>  
                      
                    <div class="form-group">
                        <div class="col-sm-4">
                            <?= FORM::label('category_icon', __('Select from files'), array('for'=>'category_icon'))?>
                            <input type="file" name="category_icon" class="form-control" id="category_icon" />
                        </div>
                    </div>
                      
                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button> 
                    
                    <?if (( $icon_src = $category->get_icon() )!==FALSE ):?>
                        <button type="submit"
                            class="btn btn-danger index-delete index-delete-inline"
                            onclick="return confirm('<?=__('Delete icon?')?>');" 
                            type="submit" 
                            name="icon_delete"
                            value="1" 
                            title="<?=__('Delete icon')?>">
                            <?=__('Delete icon')?>
                        </button>
                    <?endif?>
                </form>
            </div>
        </div>
    </div>
</div>