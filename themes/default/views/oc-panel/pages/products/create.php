<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('New Product')?></h1>
</div>

<div class=" well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'product','action'=>'create')), array('class'=>'form-horizontal product_form', 'enctype'=>'multipart/form-data'))?>
		<fieldset>

			<!-- drop down selector -->
            <div class="control-group">
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
                                <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="id_category" value="<?=$cats[$key]['id']?>"  REQUIRED> 
                                
                                 <?if ($cats[$key]['price']>0):?>
                                    <span class="label label-success">
                                    <?=i18n::money_format( $cats[$key]['price'])?>
                                    </span>
                                <?endif?>
                                
                                </label>
                                
                            <?else:?>
                                <label class="radio">
                                <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="id_category" value="<?=$cats[$key]['id']?>"  REQUIRED> 
                                
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

            <div class="control-group">
            	<?= FORM::label('type', __('Type'), array('class'=>'control-label', 'for'=>'type' ))?>
            	<div class="controls">
		            <select name="type" id="type" class="input-xlarge" REQUIRED>
		            	<option></option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
				</div>
			</div>

			<div class="control-group">
            	<?= FORM::label('currency', __('Currency'), array('class'=>'control-label', 'for'=>'currency' ))?>
            	<div class="controls">
		            <select name="currency" id="currency" class="input-xlarge" REQUIRED>
						<option></option>
						<?foreach ($currency as $curr):?>
							<option value="<?=$curr?>"><?=$curr?></option>
						<?endforeach?>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
				<div class="controls">
					<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title', 'required'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('skins', __('Skin name'), array('class'=>'control-label', 'for'=>'skins'))?>
				<div class="controls">
					<?= FORM::input('skins', Request::current()->post('skins'), array('placeholder' => __('skins'), 'class' => 'input-xlarge', 'id' => 'skins', 'required'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('url_demo', __('Url demo'), array('class'=>'control-label', 'for'=>'url_demo'))?>
				<div class="controls">
					<?= FORM::input('url_demo', Request::current()->post('url_demo'), array('placeholder' => __('http://open-eshop.com'), 'class' => 'input-xlarge', 'id' => 'url_demo', 'type' => 'url', 'required'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('version', __('Version'), array('class'=>'control-label', 'for'=>'version'))?>
				<div class="controls">
					<?= FORM::input('version', Request::current()->post('version'), array('placeholder' => '1.0.0', 'class' => 'input-xlarge', 'id' => 'version', 'type' => 'text', 'required'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
				<div class="controls">
					<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => i18n::money_format(1), 'class' => 'input-xlarge', 'id' => 'price', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('price_offer', __('Price Offer'), array('class'=>'control-label', 'for'=>'price_offer'))?>
				<div class="controls">
					<?= FORM::input('price_offer', Request::current()->post('price_offer'), array('placeholder' => i18n::money_format(1), 'class' => 'input-xlarge', 'id' => 'price_offer', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('offer_valid', __('Offer Valid'), array('class'=>'control-label', 'for'=>'offer_valid'))?>
				<div class="controls">	
					<input  type="text" class="span2" size="16" id="offer_valid" name="offer_valid"  value="" class="input-xlarge" data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

            <div class="control-group">
				<?= FORM::label('support_days', __('Support Days'), array('class'=>'control-label', 'for'=>'support_days'))?>
				<div class="controls">
					<?= FORM::input('support_days', Request::current()->post('support_days'), array('placeholder' => '365', 'class' => 'input-xlarge', 'id' => 'support_days', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('licenses', __('Licenses'), array('class'=>'control-label', 'for'=>'licenses'))?>
				<div class="controls">
					<?= FORM::input('licenses', Request::current()->post('licenses'), array('placeholder' => '1', 'class' => 'input-xlarge', 'id' => 'licenses', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('license_days', __('License Days'), array('class'=>'control-label', 'for'=>'license_days'))?>
				<div class="controls">
					<?= FORM::input('license_days', Request::current()->post('license_days'), array('placeholder' => '0', 'class' => 'input-xlarge', 'id' => 'license_days', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
				<div class="controls">
					<?= FORM::textarea('description', Request::current()->post('description'), array('class'=>'span6', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label('email_purchase_notes', __('Notes'), array('class'=>'control-label', 'for'=>'email_purchase_notes', 'spellcheck'=>TRUE))?>
				<div class="controls">
					<?= FORM::textarea('email_purchase_notes', Request::current()->post('email_purchase_notes'), array('class'=>'span6', 'name'=>'email_purchase_notes', 'id'=>'email_purchase_notes' , 'rows'=>10))?>
				</div>
			</div>
			<div class="control-group">
				<?for ($i=0; $i < core::config("advertisement.num_images") ; $i++):?> 
					<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images'.$i))?>
					<div class="controls">
						<input type="file" name="<?='image'.$i?>" id="<?='fileInput'.$i?>" />
					</div>
				<?endfor?>
			</div>
			

			<div class="form-actions">
				<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'product','action'=>'create'))))?>
				<p class="help-block"><?=__('User account will be created')?></p>
			</div>
		</fieldset>
	<?= FORM::close()?>

</div>
<!--/well-->