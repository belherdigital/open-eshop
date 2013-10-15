<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Advanced Search')?></h1>
</div>

<div class="well advise clearfix">
	<?= FORM::open(Route::url('search'), array('class'=>'navbar-search pull-left', 'method'=>'GET', 'action'=>''))?>
	<fieldset>
	    <div class="control-group">
		    <?= FORM::label('advertisement', __('Advertisement Title'), array('class'=>'control-label', 'for'=>'advertisement'))?>
		    <div class="controls">	
		    	<input type="text" id="title" name="title" class="input-xlarge" value="" placeholder="<?=__('Search')?>">
			</div>
		</div>

        <div class="control-group">
            <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' ))?>
            <div class="controls">          
                <select name="category" id="category" class="input-xlarge" >
                <option></option>
                <?function lili($item, $key,$cats){?>
                <option value="<?=$cats[$key]['seoname']?>"><?=$cats[$key]['name']?></option>
                    <?if (count($item)>0):?>
                    <optgroup label="<?=$cats[$key]['name']?>">    
                        <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                    <?endif?>
                <?}array_walk($order_categories, 'lili',$categories);?>
                </select>
            </div>
        </div>
        
        <?if(count($locations) !== 0):?>
            <div class="control-group">
                <?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location' , 'multiple'))?>
                <div class="controls">          
                    <select name="location" id="location" class="input-xlarge" >
                    <option></option>
                    <?function lolo($item, $key,$locs){?>
                    <option value="<?=$locs[$key]['seoname']?>"><?=$locs[$key]['name']?></option>
                        <?if (count($item)>0):?>
                        <optgroup label="<?=$locs[$key]['name']?>">    
                            <? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
                            </optgroup>
                        <?endif?>
                    <?}array_walk($order_locations, 'lolo',$locations);?>
                    </select>
                </div>
            </div>
        <?endif?>

		<div class="form-actions">
			<?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('search')))?> 
		</div>

	</fieldset>
	<?= FORM::close()?>
</div>


