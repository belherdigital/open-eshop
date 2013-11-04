<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('Edit Product')?></h1>
</div>

<div class=" well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'product','action'=>'update','id'=>$product->id_product)), array('class'=>'form-horizontal product_form_update', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
		<!-- file -->
		<div class="control-group">
				<div class="controls">
					<?if (is_file(DOCROOT.'data/'.$product->file_name)):?>
					    <span class="label label-info">
					        <?=strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> 
					    </span>
					    <span class="label label-info"> 
					        <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB
					    </span>
					    <span class="label label-info">
					        <?=__('Uploaded').' '.Date::unix2mysql(filemtime(DOCROOT.'data/'.$product->file_name))?> 
					    </span>
						<button class="btn btn-danger index-delete"
								   onclick="return confirm('<?=__('Delete?')?>');" 
								   type="submit" 
								   name="product_delete"
								   value="<?=$product->id_product?>" 
								   rel"tooltip" 
								   title="<?=__('Delete product')?>">
									<?=__('Delete')?>
								</button>
					<?else:?>
					
					<?= FORM::label('file_name', __('Upload product'), array('class'=>'control-label', 'for'=>'file_name'))?>
					<div class="controls">
						<input type="file" name="file_name" id="file_name" />
					</div>
					
					<?endif?>
				</div>	
			</div>
			<!-- drop down selector  CATEGORIES-->
            <div class="control-group">
                <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' ))?>
                <div class="controls"> 
                <div class="accordion" >

                <?function lili3($item, $key, $cats){?>

                    <div class="accordion-group">
                        <div class="accordion-heading"> 

                            <?if (count($item)>0):?>
                                <label class="radio">
                                	<a class="btn btn-primary btn-mini" data-toggle="collapse" type="button"  
                                   	 	data-target="#acc_<?=$cats['categories'][$key]['seoname']?>">                    
                                    	<i class=" icon-plus icon-white"></i> <?=$cats['categories'][$key]['name']?>
                                	</a>
                                <input <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'checked':''?> type="radio" id="radio_<?=$cats['categories'][$key]['seoname']?>" name="category" value="<?=$cats['categories'][$key]['id']?>" required > 
                                                        
                                </label>
                                
                            <?else:?>
                                <label class="radio">
                                <input <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'checked':''?> type="radio" id="radio_<?=$cats['categories'][$key]['seoname']?>" name="category" value="<?=$cats['categories'][$key]['id']?>" required > 
                                
                               		<a class="btn btn-mini btn-primary" data-toggle="collapse" type="button"  
                                   	 	data-target="#acc_<?=$cats['categories'][$key]['seoname']?>">                    
                                    	<?=$cats['categories'][$key]['name']?>
                                	</a>
                                </label>
                            <?endif?>
                        </div>

                        <?if (count($item)>0):?>
                            <div id="acc_<?=$cats['categories'][$key]['seoname']?>" 
                                class="accordion-body collapse <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'in':''?>">
                                <div class="accordion-inner">
                                    <? if (is_array($item)) array_walk($item, 'lili3', $cats);?>
                                </div>
                            </div>
                        <?endif?>

                    </div>
                <?}array_walk($order_categories, 'lili3', array('categories'=>$categories, 'cat_selected'=>$product->category->seoname) );?>

                </div>
                </div>
            </div>
            <!-- /categories -->

			<div class="control-group">
            	<?= FORM::label('currency', __('Currency'), array('class'=>'control-label', 'for'=>'currency' ))?>
            	<div class="controls">
		            <select name="currency" id="currency" class="input-xlarge" REQUIRED>
						<option></option>
						<?foreach ($currency as $curr):?>
							<?if($curr == $product->currency):?>
							<option value="<?=$curr?>" selected><?=$curr?></option>
							<?else:?>
							<option value="<?=$curr?>"><?=$curr?></option>
							<?endif?>
						<?endforeach?>
					</select>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
				<div class="controls">
					<?= FORM::input('title', $product->title, array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title', 'required'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('url_demo', __('Url demo'), array('class'=>'control-label', 'for'=>'url_demo'))?>
				<div class="controls">
					<?= FORM::input('url_demo', $product->url_demo, array('placeholder' => __('http://open-eshop.com'), 'class' => 'input-xlarge', 'id' => 'url_demo', 'type' => 'url'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('version', __('Version'), array('class'=>'control-label', 'for'=>'version'))?>
				<div class="controls">
					<?= FORM::input('version', $product->version, array('placeholder' => '1.0.0', 'class' => 'input-xlarge', 'id' => 'version', 'type' => 'text', 'required'))?>
				</div>
			</div>

			<div class="controls">
				<?= FORM::input('skins', $product->skins, array('placeholder' => __('skins'), 'class' => 'input-xlarge', 'type' => 'hidden'))?>
			</div>

			<div class="control-group">
				<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
				<div class="controls">
					<?= FORM::input('price', $product->price, array('placeholder' => i18n::money_format(1), 'class' => 'input-xlarge', 'id' => 'price', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('price_offer', __('Price Offer'), array('class'=>'control-label', 'for'=>'price_offer'))?>
				<div class="controls">
					<?= FORM::input('price_offer', $product->price_offer, array('placeholder' => i18n::money_format(1), 'class' => 'input-xlarge', 'id' => 'price_offer', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('offer_valid', __('Offer Valid'), array('class'=>'control-label', 'for'=>'offer_valid'))?>
				<div class="controls">	
					<input  type="text" class="span2" size="16" id="offer_valid" name="offer_valid"  value="<?=$product->offer_valid?>" class="input-xlarge" data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

            <div class="control-group">
				<?= FORM::label('support_days', __('Support Days'), array('class'=>'control-label', 'for'=>'support_days'))?>
				<div class="controls">
					<?= FORM::input('support_days', $product->support_days, array('placeholder' => '365', 'class' => 'input-xlarge', 'id' => 'support_days', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('licenses', __('Licenses'), array('class'=>'control-label', 'for'=>'licenses'))?>
				<div class="controls">
					<?= FORM::input('licenses', $product->licenses, array('placeholder' => '1', 'class' => 'input-xlarge', 'id' => 'licenses', 'type'=>'text'))?>
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label('license_days', __('License Days'), array('class'=>'control-label', 'for'=>'license_days'))?>
				<div class="controls">
					<?= FORM::input('license_days', $product->license_days, array('placeholder' => '0', 'class' => 'input-xlarge', 'id' => 'license_days', 'type'=>'text'))?>
				</div>
			</div>

            <div class="control-group">
                <?= FORM::label('featured', __('Feature product'), array('class'=>'control-label', 'for'=>'featured'))?>
                <div class="controls">  
                    <input  type="text" class="span2" size="16" id="featured" name="featured"  value="<?=$product->featured?>" class="input-xlarge" data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

			<div class="control-group">
				<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
				<div class="controls">
					<?= FORM::textarea('description', $product->description, array('class'=>'span6', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label('email_purchase_notes', __('Notes'), array('class'=>'control-label', 'for'=>'email_purchase_notes', 'spellcheck'=>TRUE))?>
				<div class="controls">
					<?= FORM::textarea('email_purchase_notes', $product->email_purchase_notes, array('class'=>'span6', 'name'=>'email_purchase_notes', 'id'=>'email_purchase_notes' , 'rows'=>10))?>
				</div>
			</div>
			<!-- images -->
			<div class="control-group">
					<div class="controls">
						<?$images = $product->get_images()?>
						<?if($images):?>
						<ul class="thumbnails">
							<?php foreach ($images as $path => $value):?>
							<?if(isset($value['thumb'])): // only formated images (not originals)?>
							<?$img_name = str_replace(".jpg", "", substr(strrchr($value['thumb'], "/"), 1 ));?>
							<li>
								<a class="thumbnail">
									<img src="<?=URL::base('http')?><?= $value['thumb']?>" class="img-rounded" alt="">
								</a>
								
								<button class="btn btn-danger index-delete"
								   onclick="return confirm('<?=__('Delete?')?>');" 
								   type="submit" 
								   name="img_delete"
								   value="<?=$img_name?>" 
								   rel"tooltip" 
								   title="<?=__('Delete image')?>">
									<?=__('Delete')?>
								</button>
							</li>
							<?endif?>
							<?endforeach?>
						</ul>
						<?endif?>
					</div>	
				</div>
				<!-- ./end images -->
				<div class="control-group">
					<?if (core::config('product.num_images') > count($images)):?> <!-- permition to add more images-->
						<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images0'))?>
						<div class="controls">
							<input class="input-file" type="file" name='image' id='fileInput0' />
						</div>
					<?endif?>
				</div>
				<label class="checkbox">
			      <input type="checkbox" name="notify"> <?=__('Notify all buyers on this update')?>
			    </label>
			<div class="form-actions">
				<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'product','action'=>'update','id'=>$product->id_product))))?>
			</div>
		</fieldset>
	<?= FORM::close()?>

</div>
<!--/well-->