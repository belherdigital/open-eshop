<?php defined('SYSPATH') or die('No direct script access.');?>


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
		<a class="oe_button" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)) ?>"><?=__('Buy Now')?> <?=$product->formated_price()?></a></textarea>

	<div class="clearfix"></div><br>
		<a class="btn btn-primary" target="_blank" 
		        href="http://panel.adserum.com/new-advertisement.html?name=<?=$product->user->name?>&email=<?=$product->user->email?>&title=<?=$product->title?>&desc=<?=$product->description?>&desc2=<?=__('Buy Now')?> <?=$product->formated_price()?>&url=<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>&durl=<?=core::config('general.base_url')?>">
		        <i class="glyphicon glyphglyphicon glyphicon-globe"></i> <?=__('Promote at Adserum')?>
		</a>
	
	</p>
</div>

<?=Form::errors()?>
<?= FORM::open(Route::url('oc-panel',array('controller'=>'product','action'=>'update','id'=>$product->id_product)), array('class'=>'form-horizontal product_form', 'enctype'=>'multipart/form-data'))?>
	<fieldset>
	<!-- file -->
	<div class="col-md-4 col-sm-6 col-xs-6">
		<div class="panel panel-primary product-panel">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-cog"></span> <?=__('General information')?></h3>
			</div>
  			<div class="panel-body">
  				<!-- drop down selector  CATEGORIES-->
		        <div class="form-group">
		            <label for="category" class="col-md-12"><?=__('Category')?> <span class="star-required">*</span></label>
		            <div class="col-md-12"> 
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
			    	<label for="currency" class="col-md-12"><?=__('Currency')?> <span class="star-required">*</span></label>
			    	<div class="col-md-12">
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
					<label for="title" class="col-md-12"><?=__('Title')?> <span class="star-required">*</span></label>
					<div class="col-md-12">
						<?= FORM::input('title', $product->title, array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-md-12"><?=__('Description')?> <span class="star-required">*</span></label>
					<div class="col-md-12">
						<?= FORM::textarea('description', $product->description, array('class'=>'form-control', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
					</div>
				</div>
			</div>
  		</div>
	</div>
	<div class="col-md-4 col-sm-6 col-xs-6">
		<div class="panel panel-primary product-panel">
			<div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> <?=__('Details')?></div>
  			<div class="panel-body">
	  			<div class="form-group">
					<?= FORM::label('price', __('Price'), array('class'=>'col-md-12', 'for'=>'price'))?>
					<div class="col-md-12">
						<?= FORM::input('price', $product->price, array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price', 'type'=>'text'))?>
					</div>
				</div>

				<div class="form-group">
					<?= FORM::label('price_offer', __('Price Offer'), array('class'=>'col-md-12', 'for'=>'price_offer'))?>
					<div class="col-md-12">
						<?= FORM::input('price_offer', $product->price_offer, array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price_offer', 'type'=>'text'))?>
					</div>
				</div>

				<div class="form-group">
					<?= FORM::label('offer_valid', __('Offer Valid'), array('class'=>'col-md-12', 'for'=>'offer_valid'))?>
					<div class="col-md-8">	
						<input  type="text" size="16" id="offer_valid" name="offer_valid"  value="<?=$product->offer_valid?>" class="form-control" data-date="" data-date-format="yyyy-mm-dd">
		            </div>
		        </div>

				<hr>
	            <div class="panel-title">
			    	<h4><small><?=__('Support Details')?></small></h4> 
			  	</div>
		        <div class="form-group">
					<?= FORM::label('support_days', __('Support Days'), array('class'=>'col-md-12', 'for'=>'support_days'))?>
					<div class="col-md-12">
						<?= FORM::input('support_days', $product->support_days, array('placeholder' => '365', 'class' => 'form-control', 'id' => 'support_days', 'type'=>'text'))?>
					</div>
				</div>

				<div class="form-group">
					<?= FORM::label('licenses', __('Licenses'), array('class'=>'col-md-12', 'for'=>'licenses'))?>
					<div class="col-md-12">
						<?= FORM::input('licenses', $product->licenses, array('placeholder' => '1', 'class' => 'form-control', 'id' => 'licenses', 'type'=>'text'))?>
					</div>
				</div>

				<div class="form-group">
					<?= FORM::label('license_days', __('License Days'), array('class'=>'col-md-12', 'for'=>'license_days'))?>
					<div class="col-md-12">
						<?= FORM::input('license_days', $product->license_days, array('placeholder' => '0', 'class' => 'form-control', 'id' => 'license_days', 'type'=>'text'))?>
					</div>
				</div>
  			</div>
  		</div>
  	</div>

  	<div class="col-md-4 col-sm-6 col-xs-6">
		<div class="panel panel-primary product-panel">
			<div class="panel-heading"><span class="glyphicon glyphicon-pencil"></span> <?=__('Additional information')?></div>
  			<div class="panel-body">

	  			<div class="form-group">
					<?= FORM::label('url_demo', __('Url demo'), array('class'=>'col-md-12', 'for'=>'url_demo'))?>
					<div class="col-md-12">
						<?= FORM::input('url_demo', $product->url_demo, array('placeholder' => __('http://open-eshop.com'), 'class' => 'form-control', 'id' => 'url_demo', 'type' => 'url'))?>
					</div>
				</div>

				<div class="form-group">
					<?= FORM::label('version', __('Version'), array('class'=>'col-md-12', 'for'=>'version'))?>
					<div class="col-md-12">
						<?= FORM::input('version', $product->version, array('placeholder' => '1.0.0', 'class' => 'form-control', 'id' => 'version', 'type' => 'text'))?>
					</div>
				</div>

		         <div class="form-group">
		            <?= FORM::label('skins', __('Skins'), array('class'=>'col-md-12', 'for'=>'skins'))?>
		            <div class="col-md-12">
		                <?= FORM::input('skins', $product->skins, array('placeholder' => 'Hit enter to confirm', 'class' => 'form-control', 'id' => 'skins', 'type' => 'text','data-role'=>'tagsinput'))?>
		            </div>
		        </div>

				<div class="form-group">
		            <?= FORM::label('featured', __('Feature product'), array('class'=>'col-md-12', 'for'=>'featured'))?>
		            <div class="col-md-8">  
		                <input  type="text" size="16" id="featured" name="featured"  value="<?=$product->featured?>" class="form-control" data-date="" data-date-format="yyyy-mm-dd">
		            </div>
		        </div>

				<div class="form-group">
					<?= FORM::label('email_purchase_notes', __('Notes'), array('class'=>'col-md-12', 'for'=>'email_purchase_notes', 'spellcheck'=>TRUE))?>
					<div class="col-md-12">
						<?= FORM::textarea('email_purchase_notes', $product->email_purchase_notes, array('class'=>'form-control', 'name'=>'email_purchase_notes', 'id'=>'email_purchase_notes' , 'rows'=>10))?>
					</div>
				</div>
  			</div>
  		</div>
  	</div>
	<!-- PRODUCT FILES -->
	<div class="clearfix"></div>
	<div class="col-md-12">
		<div class="panel panel-primary product-panel">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-file"></span> <?=__('Upload files')?></h3>
			</div>
  			<div class="panel-body">
	  			<div class="form-group">
					<?$images = $product->get_images()?>
					<?if($images):?>
					
						<?php foreach ($images as $path => $value):?>
						<?if(isset($value['thumb'])): // only formated images (not originals)?>
						<?$img_name = str_replace(".jpg", "", substr(strrchr($value['thumb'], "/"), 1 ));?>
						<div class="col-md-3">
							<a class="thumbnail">
								<img src="<?=URL::base()?><?= $value['thumb']?>" class="img-rounded" alt="">
							</a>
							
							<button class="btn btn-danger index-delete pull-right"
							   onclick="return confirm('<?=__('Delete?')?>');" 
							   type="submit" 
							   name="img_delete"
							   value="<?=$img_name?>" 
							   rel"tooltip" 
							   title="<?=__('Delete image')?>">
								<?=__('Delete')?>
							</button>
						</div>
						<?endif?>
						<?endforeach?>
					
					<?endif?>
				</div>
				<div class="clearfix"></div>	
				<!-- ./end images -->
				<div class="form-group">
					<?if (core::config('product.num_images') > count($images)):?> <!-- permition to add more images-->
						<div class="fileinput fileinput-new" data-provides="fileinput">
						  	<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
						    	<img src="http://www.placehold.it/200x150&text=Image" alt="<?=__('Image')?>">
						  	</div>
						  	<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
						  	<div>
						    	<span class="btn btn-default btn-file">
						    	<span class="fileinput-new"><?=__('Select image')?></span>
						    	<span class="fileinput-exists"><?=__('Change')?></span>
						    	<input type="file" name='image' id='fileInput0'/></span>
						    	<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?=__('Remove')?></a>
						  	</div>
						</div>
					<?endif?>
				</div>
				<hr>
				<div class="panel-title">
			    	<h2><small><?=__('Digital file')?></small></h2> 
			  	</div>
				<div class="form-group alert alert-primary">
					<?if ($product->has_file()==TRUE):?>
						<div class="pull-left">
						<a class="thumbnail">
							<img src="http://www.placehold.it/100x100&text=<?=strtoupper(strrchr($product->file_name, '.'))?>" alt="<?=strtoupper(strrchr($product->file_name, '.'))?>">
						</a>
						</div>	
							<h4>
							    <span class="label label-info"> 
							        <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB
							    </span>
							    <span class="label label-info">
							        <?=__('Uploaded').' '.Date::unix2mysql(filemtime(DOCROOT.'data/'.$product->file_name))?> 
							    </span>
							</h4>
							<button class="btn btn-danger index-delete "
									onclick="return confirm('<?=__('Delete?')?>');" 
								   	type="submit" 
								   	name="product_delete"
								   	value="<?=$product->id_product?>" 
								   	rel"tooltip" 
								   	title="<?=__('Delete product')?>">
							<?=__('Delete')?>
							</button>
					
					<?else:?>
						<div class="col-md-12">
						    <div class="clearfix"></div> <br>
							<span class="btn btn-success fileinput-button">
						        <i class="glyphicon glyphicon-plus"></i>
						        <span><?=__('Add File')?></span>  
						        <input id="fileupload" type="file" data-url="../upload" name="fileupload" data-size="<?=core::config('product.max_size')*1024*1024?>">
						    </span>
						    <div class="clearfix"></div><br>
						
							<div class="drop-down-box"><span class="fileinput-new"><?=__('Drag & Drop file here')?></span></div>
						
							<div class="clearfix"></div> <br>
							<div id="progress" class="progress">
						        <div class="bar progress-bar progress-bar-success" style="width: 0%;"></div>
						    </div>

							<input id="uploadedfile" type="hidden" name="file_name">
							<div id="name-files" class="name-files"></div>
							<div id="files" class="files"></div>
							<button id="delete-button-file" class="hide btn btn-danger"><?=__('Delete')?></button>
						</div>

						<div class="clearfix"></div><br>
					<?endif?>	
				</div>
			    
				<div class="clearfix"></div>
				<div class="pull-right">
					<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-lg btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'product','action'=>'update','id'=>$product->id_product))))?>
					<div class="checkbox">				
						<label>
					      <input type="checkbox" name="notify"> <?=__('Notify all buyers on this update')?>
					    </label>
					</div>
					<div class="checkbox">
				        <label>
				          	<input type="checkbox" name="status" <?=($product->status==Model_Product::STATUS_ACTIVE)?'checked="checked"':''?>>&nbsp;<?=__('Active')?>?
				        </label>
			      	</div>
				</div>
  			</div>
  		</div>
  	</div>
			
	</fieldset>
<?= FORM::close()?>
