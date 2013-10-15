<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->subscribe_title?></h3>

<?= FORM::open(Route::url('default', array('controller'=>'subscribe', 'action'=>'index','id'=>$widget->user_id)), array( 'enctype'=>'multipart/form-data'))?>
<!-- if categories on show selector of categories -->
	<?if($widget->cat_items !== NULL):?>
		<div class="control-group">
			<?= FORM::label('category_subscribe', __('Categories'), array('class'=>'control-label', 'for'=>'category_subscribe'))?>
			<div class="controls">
				<select name="category_subscribe[]" id="category_subscribe" class="span2" multiple="true" required>
	            <option></option>
	            <?function lili15($item, $key,$cats){?>
	            <?if ( count($item)==0 AND $cats[$key]['id_category_parent'] != 1):?>
	            <option value="<?=$key?>" style="margin-left:10px;"><?=$cats[$key]['name']?></option>
	            <?endif?>
	                <?if ($cats[$key]['id_category_parent'] == 1 OR count($item)>0):?>
	                <option value="<?=$key?>" style="font-weight:bold;"> <?=$cats[$key]['name']?> </option>  
	                    <? if (is_array($item)) array_walk($item, 'lili15', $cats);?>
	                <?endif?>
	            <?}
	            $cat_order = $widget->cat_order_items; 
	        	array_walk($cat_order , 'lili15', $widget->cat_items);?>
	            </select> 
			</div>
		</div>
	<?endif?>
<!-- end categories/ -->
<!-- locations -->
<?if($widget->loc_items !== NULL):?>
	<?if(count($widget->loc_items) > 1 AND core::config('advertisement.location') != FALSE):?>
	    <div class="control-group">
	        <?= FORM::label('location_subscribe', __('Location'), array('class'=>'control-label', 'for'=>'location_subscribe' ))?>
	        <div class="controls">          
	            <select name="location_subscribe[]" id="location_subscribe" class="span2" required>
	            <option></option>
	            <?function lolo10($item, $key,$locs){?>
	            <option value="<?=$key?>"><?=$locs[$key]['name']?></option>
	                <?if (count($item)>0):?>
	                <optgroup label="<?=$locs[$key]['name']?>_subscribe">    
	                    <? if (is_array($item)) array_walk($item, 'lolo10', $locs);?>
	                    </optgroup>
	                <?endif?>
	            <?}
	            $loc_order = $widget->loc_order_Witems; 
	        	array_walk($loc_order , 'lolo10',$widget->loc_items);?>
	            </select>
	        </div>
	    </div>
	<?endif?>
<?endif?>
<!-- end locatins -->

<?if($widget->user_email == NULL):?>
	<div class="control-group">
		<?= FORM::label('email_subscribe', __('Email'), array('class'=>'control-label', 'for'=>'email_subscribe'))?>
		<div class="controls">
			<?= FORM::input('email_subscribe', Request::current()->post('email_subscribe'), array('class'=>'span2', 'id'=>'email_subscribe', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
		</div>
	</div>
<?else:?>
	<div class="control-group">
		<div class="controls">
			<?= FORM::input('email_subscribe', $widget->user_email, array('class'=>'span2', 'id'=>'email_subscribe', 'type'=>'hidden' ,'required','placeholder'=>__('Email')))?>
		</div>
	</div>
<?endif?>
<?if($widget->price != FALSE):?>
	<!-- slider -->
	<div class="control-group">
		<div class="controls">
			<?= FORM::label('price_subscribe', __('Price'), array('class'=>'control-label', 'for'=>'price_subscribe'))?>
			<input type="text" class="span2 slider_subscribe" value="<?=$widget->min_price?>,<?=$widget->max_price?>" 
					data-slider-min='<?=$widget->min_price?>' data-slider-max="<?=$widget->max_price?>" 
					data-slider-step="50" data-slider-value='[<?=$widget->min_price?>,<?=$widget->max_price?>]' 
					data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" name='price_subscribe' >
		</div>
	</div>
<?else:?>
	<input type="hidden" value='0,0'>
<?endif?>
	<div class="form-actions">
		<?= FORM::button('submit', __('Subscribe'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'subscribe', 'action'=>'index','id'=>$widget->user_id))))?>
		
	</div>
	<?if($widget->subscriber):?>
		<a href="<?=Route::url('default', array('controller'=>'subscribe', 'action'=>'unsubscribe', 'id'=>$widget->user_id))?>"><?=__('Unsubscribe')?></a>
	<?endif?>
<?= FORM::close()?>
