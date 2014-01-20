<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('Edit Product')?></h1>
    <p>
        <?=__('Sell your product')?>:<br>
        <?=__('Link')?>
	
    <a target="_blank" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)) ?>">
        <?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)) ?>
    </a>

	<br>
	<p><?=__('Button with overlay')?>:</p>
	<textarea class="col-md-4" onclick="this.select()"><script src="<?=Core::config('general.base_url')?>embed.js?v=<?=core::version?>"></script>
		<a class="oe_button" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)) ?>"><?=__('Buy Now')?> <?=$product->final_price().' '.$product->currency?></a></textarea>

	<div class="clearfix"></div><br>
		<a class="btn btn-primary" target="_blank" 
		        href="http://panel.adserum.com/new-advertisement.html?name=<?=$product->user->name?>&email=<?=$product->user->email?>&title=<?=$product->title?>&desc=<?=$product->description?>&desc2=<?=__('Buy Now')?> <?=$product->final_price().' '.$product->currency?>&url=<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>&durl=<?=core::config('general.base_url')?>">
		        <i class="glyphicon glyphglyphicon glyphicon-globe"></i> <?=__('Promote at Adserum')?>
		</a>
	
	</p>
</div>


<div class=" well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'product','action'=>'update','id'=>$product->id_product)), array('class'=>'form-horizontal product_form_update', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
		<!-- file -->
			<div class="form-group">
				<?if (is_file(DOCROOT.'data/'.$product->file_name)):?>
				<div class="col-sm-offset-3">
					<h4>
					    <span class="label label-info">
					        <?=strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> 
					    </span>
					    <span class="label label-info"> 
					        <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB
					    </span>
					    <span class="label label-info">
					        <?=__('Uploaded').' '.Date::unix2mysql(filemtime(DOCROOT.'data/'.$product->file_name))?> 
					    </span>
					</h4>
					<button class="btn btn-danger index-delete"
							onclick="return confirm('<?=__('Delete?')?>');" 
						   	type="submit" 
						   	name="product_delete"
						   	value="<?=$product->id_product?>" 
						   	rel"tooltip" 
						   	title="<?=__('Delete product')?>">
					<?=__('Delete')?>
					</button>
				</div>
				<?else:?>

					<?= FORM::label('file_name', __('Upload product'), array('class'=>'col-md-3 control-label', 'for'=>'file_name'))?>
					<div class="col-sm-4 col-xs-11">
						<input class="form-control" type="file" name="file_name" id="file_name" />
					</div>

				<?endif?>	
			</div>
			<!-- drop down selector  CATEGORIES-->
            <div class="form-group">
                <?= FORM::label('category', __('Category'), array('class'=>'col-md-3 control-label', 'for'=>'category' ))?>
                <div class="col-md-5"> 
                <div class="accordion" >

                <?function lili3($item, $key, $cats){?>

                    <div class="accordion-group">
                        <div class="accordion-heading"> 

                            <?if (count($item)>0):?>
                                <label class="radio">
                                	<a class="btn btn-primary btn-xs" data-toggle="collapse" type="button"  
                                   	 	data-target="#acc_<?=$cats['categories'][$key]['seoname']?>">                    
                                    	<i class=" glyphicon glyphicon-plus"></i> <?=$cats['categories'][$key]['name']?>
                                	</a>
                                <input <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'checked':''?> type="radio" id="radio_<?=$cats['categories'][$key]['seoname']?>" name="id_category" value="<?=$cats['categories'][$key]['id']?>" required > 
                                                        
                                </label>
                                
                            <?else:?>
                                <label class="radio">
                                <input <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'checked':''?> type="radio" id="radio_<?=$cats['categories'][$key]['seoname']?>" name="id_category" value="<?=$cats['categories'][$key]['id']?>" required > 
                                
                               		<a class="btn btn-xs btn-primary" data-toggle="collapse" type="button"  
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

			<div class="form-group">
            	<?= FORM::label('currency', __('Currency'), array('class'=>'col-md-3 control-label', 'for'=>'currency' ))?>
            	<div class="col-md-5">
		            <select name="currency" id="currency" class="form-control" REQUIRED>
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

			<div class="form-group">
				<?= FORM::label('title', __('Title'), array('class'=>'col-md-3 control-label', 'for'=>'title'))?>
				<div class="col-md-5">
					<?= FORM::input('title', $product->title, array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('url_demo', __('Url demo'), array('class'=>'col-md-3 control-label', 'for'=>'url_demo'))?>
				<div class="col-md-5">
					<?= FORM::input('url_demo', $product->url_demo, array('placeholder' => __('http://open-eshop.com'), 'class' => 'form-control', 'id' => 'url_demo', 'type' => 'url'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('version', __('Version'), array('class'=>'col-md-3 control-label', 'for'=>'version'))?>
				<div class="col-md-5">
					<?= FORM::input('version', $product->version, array('placeholder' => '1.0.0', 'class' => 'form-control', 'id' => 'version', 'type' => 'text'))?>
				</div>
			</div>

             <div class="form-group">
                <?= FORM::label('skins', __('Skins'), array('class'=>'col-md-3 control-label', 'for'=>'skins'))?>
                <div class="col-md-5">
                    <?= FORM::input('skins', $product->skins, array('placeholder' => 'Comma separated', 'class' => 'form-control', 'id' => 'skins', 'type' => 'text'))?>
                </div>
            </div>

			<div class="form-group">
				<?= FORM::label('price', __('Price'), array('class'=>'col-md-3 control-label', 'for'=>'price'))?>
				<div class="col-md-5">
					<?= FORM::input('price', $product->price, array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('price_offer', __('Price Offer'), array('class'=>'col-md-3 control-label', 'for'=>'price_offer'))?>
				<div class="col-md-5">
					<?= FORM::input('price_offer', $product->price_offer, array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price_offer', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('offer_valid', __('Offer Valid'), array('class'=>'col-md-3 control-label', 'for'=>'offer_valid'))?>
				<div class="col-md-3">	
					<input  type="text" size="16" id="offer_valid" name="offer_valid"  value="<?=$product->offer_valid?>" class="form-control" data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

            <div class="form-group">
				<?= FORM::label('support_days', __('Support Days'), array('class'=>'col-md-3 control-label', 'for'=>'support_days'))?>
				<div class="col-md-5">
					<?= FORM::input('support_days', $product->support_days, array('placeholder' => '365', 'class' => 'form-control', 'id' => 'support_days', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('licenses', __('Licenses'), array('class'=>'col-md-3 control-label', 'for'=>'licenses'))?>
				<div class="col-md-5">
					<?= FORM::input('licenses', $product->licenses, array('placeholder' => '1', 'class' => 'form-control', 'id' => 'licenses', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('license_days', __('License Days'), array('class'=>'col-md-3 control-label', 'for'=>'license_days'))?>
				<div class="col-md-5">
					<?= FORM::input('license_days', $product->license_days, array('placeholder' => '0', 'class' => 'form-control', 'id' => 'license_days', 'type'=>'text'))?>
				</div>
			</div>

            <div class="form-group">
                <?= FORM::label('featured', __('Feature product'), array('class'=>'col-md-3 control-label', 'for'=>'featured'))?>
                <div class="col-md-3">  
                    <input  type="text" size="16" id="featured" name="featured"  value="<?=$product->featured?>" class="form-control" data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

			<div class="form-group">
				<?= FORM::label('description', __('Description'), array('class'=>'col-md-3 control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
				<div class="col-md-9">
					<?= FORM::textarea('description', $product->description, array('class'=>'col-md-9 col-sm-9 col-xs-12', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label('email_purchase_notes', __('Notes'), array('class'=>'col-md-3 control-label', 'for'=>'email_purchase_notes', 'spellcheck'=>TRUE))?>
				<div class="col-md-9">
					<?= FORM::textarea('email_purchase_notes', $product->email_purchase_notes, array('class'=>'col-md-9 col-sm-9 col-xs-12', 'name'=>'email_purchase_notes', 'id'=>'email_purchase_notes' , 'rows'=>10))?>
				</div>
			</div>
			<!-- images -->
			<div class="form-group">
					<div class="col-md-5">
						<?$images = $product->get_images()?>
						<?if($images):?>
						<ul class="thumbnails">
							<?php foreach ($images as $path => $value):?>
							<?if(isset($value['thumb'])): // only formated images (not originals)?>
							<?$img_name = str_replace(".jpg", "", substr(strrchr($value['thumb'], "/"), 1 ));?>
							<li>
								<a class="thumbnail">
									<img src="<?=URL::base()?><?= $value['thumb']?>" class="img-rounded" alt="">
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
				<div class="form-group">
					<?if (core::config('product.num_images') > count($images)):?> <!-- permition to add more images-->
						<?= FORM::label('images', __('Images'), array('class'=>'col-md-3 control-label', 'for'=>'images0'))?>
						<div class="col-md-5">
							<input class="input-file" type="file" name='image' id='fileInput0' />
						</div>
					<?endif?>
				</div>

				<div class="form-group">
				    <div class="col-sm-offset-3 col-sm-10">
				      	<div class="checkbox">
					        <label>
					          	<input type="checkbox" name="status" value="<?=($product->status==Model_Product::STATUS_ACTIVE)?'checked="checked"':''?>" checked="checked">  &nbsp; <?=__('Active')?>?
					        </label>
				      	</div>
				    </div>
				</div>
				<div class="form-group">
				    <div class="col-sm-offset-3 col-sm-10">
				    	<div class="checkbox">				
							<label>
						      <input type="checkbox" name="notify"> <?=__('Notify all buyers on this update')?>
						    </label>
						</div>
					</div>
				</div>

			    
			<div class="page-header"></div>
				<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-lg btn-primary col-sm-offset-3', 'action'=>Route::url('oc-panel',array('controller'=>'product','action'=>'update','id'=>$product->id_product))))?>
			
		</fieldset>
	<?= FORM::close()?>

</div>
<!--/well-->