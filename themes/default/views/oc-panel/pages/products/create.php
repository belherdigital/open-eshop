<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('New Product')?></h1>
</div>

<div class=" well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'product','action'=>'create')), array('class'=>'form-horizontal product_form', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
			<div class="form-group">
			<?= FORM::label('file_name', __('Upload product'), array('class'=>'col-md-3 control-label', 'for'=>'file_name'))?>
				<div class="col-md-5">
					<input type="file" name="file_name" id="file_name" />
				</div>
			</div>
			<!-- drop down selector -->
            <div class="form-group">
                <?= FORM::label('category', __('Category'), array('class'=>'col-md-3 control-label', 'for'=>'category' ))?>
                <div class="col-md-5"> 

                <?function lili3($item, $key,$cats){?>
                    <div class="accordion-group">
                        <div class="accordion-heading"> 

                            <?if (count($item)>0):?>
                                <label class="radio">
                                	<a class="btn btn-primary btn-xs" data-toggle="collapse" type="button"  
                                   	 	data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                    	<i class=" glyphicon glyphicon-plus"></i> <?=$cats[$key]['name']?>
                                	</a>
                                <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="id_category" value="<?=$cats[$key]['id']?>"  REQUIRED> 
                                                                
                                </label>
                                
                            <?else:?>
                                <label class="radio">
                                <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="id_category" value="<?=$cats[$key]['id']?>"  REQUIRED> 
                                
                               		<a class="btn btn-xs btn-primary" data-toggle="collapse" type="button"  
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

			<div class="form-group">
            	<?= FORM::label('currency', __('Currency'), array('class'=>'col-md-3 control-label', 'for'=>'currency' ))?>
            	<div class="col-md-5">
		            <select name="currency" id="currency" class="form-control" REQUIRED>
						<option></option>
						<?foreach ($currency as $curr):?>
							<option value="<?=$curr?>"><?=$curr?></option>
						<?endforeach?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('title', __('Title'), array('class'=>'col-md-3 control-label', 'for'=>'title'))?>
				<div class="col-md-5">
					<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('url_demo', __('Url demo'), array('class'=>'col-md-3 control-label', 'for'=>'url_demo'))?>
				<div class="col-md-5">
					<?= FORM::input('url_demo', Request::current()->post('url_demo'), array('placeholder' => __('http://open-eshop.com'), 'class' => 'form-control', 'id' => 'url_demo', 'type' => 'url'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('version', __('Version'), array('class'=>'col-md-3 control-label', 'for'=>'version'))?>
				<div class="col-md-5">
					<?= FORM::input('version', Request::current()->post('version'), array('placeholder' => '1.0.0', 'class' => 'form-control', 'id' => 'version', 'type' => 'text'))?>
				</div>
			</div>

            <div class="form-group">
                <?= FORM::label('skins', __('Skins'), array('class'=>'col-md-3 control-label', 'for'=>'skins'))?>
                <div class="col-md-5">
                    <?= FORM::input('skins', Request::current()->post('skins'), array('placeholder' => 'Comma separated', 'class' => 'form-control', 'id' => 'skins', 'type' => 'text'))?>
                </div>
            </div>
			
			<div class="col-md-5">
				<?= FORM::input('skins', Request::current()->post('skins'), array('placeholder' => __('skins'), 'class' => 'form-control', 'type' => 'hidden'))?>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<?= FORM::label('price', __('Price'), array('class'=>'col-md-3 control-label', 'for'=>'price'))?>
				<div class="col-md-5">
					<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('price_offer', __('Price Offer'), array('class'=>'col-md-3 control-label', 'for'=>'price_offer'))?>
				<div class="col-md-5">
					<?= FORM::input('price_offer', Request::current()->post('price_offer'), array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price_offer', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('offer_valid', __('Offer Valid'), array('class'=>'col-md-3 control-label', 'for'=>'offer_valid'))?>
				<div class="col-md-2">	
					<input  type="text" size="16" id="offer_valid" name="offer_valid"  value="" class="form-control " data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

            <div class="form-group">
				<?= FORM::label('support_days', __('Support Days'), array('class'=>'col-md-3 control-label', 'for'=>'support_days'))?>
				<div class="col-md-5">
					<?= FORM::input('support_days', Request::current()->post('support_days'), array('placeholder' => '365', 'class' => 'form-control', 'id' => 'support_days', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('licenses', __('Licenses'), array('class'=>'col-md-3 control-label', 'for'=>'licenses'))?>
				<div class="col-md-5">
					<?= FORM::input('licenses', Request::current()->post('licenses'), array('placeholder' => '1', 'class' => 'form-control', 'id' => 'licenses', 'type'=>'text'))?>
				</div>
			</div>

			<div class="form-group">
				<?= FORM::label('license_days', __('License Days'), array('class'=>'col-md-3 control-label', 'for'=>'license_days'))?>
				<div class="col-md-5">
					<?= FORM::input('license_days', Request::current()->post('license_days'), array('placeholder' => '0', 'class' => 'form-control', 'id' => 'license_days', 'type'=>'text'))?>
				</div>
			</div>

            <div class="form-group">
                <?= FORM::label('featured', __('Feature product'), array('class'=>'col-md-3 control-label', 'for'=>'featured'))?>
                <div class="col-md-2">  
                    <input  type="text" size="16" id="featured" name="featured"  value="" class="form-control" data-date="" data-date-format="yyyy-mm-dd">
                </div>
            </div>

			<div class="form-group">
				<?= FORM::label('description', __('Description'), array('class'=>'col-md-3 control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
				<div class="col-md-9">
					<?= FORM::textarea('description', Request::current()->post('description'), array('class'=>'col-md-9 col-sm-9 col-xs-12', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label('email_purchase_notes', __('Notes'), array('class'=>'col-md-3 control-label', 'for'=>'email_purchase_notes', 'spellcheck'=>TRUE))?>
				<div class="col-md-9">
					<?= FORM::textarea('email_purchase_notes', Request::current()->post('email_purchase_notes'), array('class'=>'col-md-9 col-sm-9 col-xs-12', 'name'=>'email_purchase_notes', 'id'=>'email_purchase_notes' , 'rows'=>10))?>
				</div>
			</div>
			
			
			<?for ($i=0; $i < core::config('product.num_images') ; $i++):?>
			<div class="form-group"> 
				<?= FORM::label('images', __('Images'), array('class'=>'col-md-3 control-label', 'for'=>'images'.$i))?>
				<div class="col-sm-4 col-xs-11">
				
					<input class="form-control" type="file" name="<?='image'.$i?>" id="<?='fileInput'.$i?>" />
				</div>
			</div>
			<?endfor?>
		
			<div class="form-group">
			    <div class="col-sm-offset-3 col-sm-10">
			      	<div class="checkbox">
				        <label>
				          	<input type="checkbox" name="status" value="" checked="checked">  &nbsp; <?=__('Active')?>?
				        </label>
			      	</div>
			    </div>
			 </div>
            
			<div class="page-header"></div>
				<?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary btn-lg col-sm-offset-3', 'action'=>Route::url('oc-panel',array('controller'=>'product','action'=>'create'))))?>

		</fieldset>
	<?= FORM::close()?>

</div>
<!--/well-->