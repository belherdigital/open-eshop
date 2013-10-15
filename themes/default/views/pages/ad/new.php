<?php defined('SYSPATH') or die('No direct script access.');?>
	<div class="page-header">
		<h1><?=__('Publish new advertisement')?></h1>
	</div>
	<div class=" well">
		<?= FORM::open(Route::url('post_new',array('controller'=>'new','action'=>'index')), array('class'=>'form-horizontal post_new', 'enctype'=>'multipart/form-data'))?>
			<fieldset>

				<div class="control-group">
					<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
					<div class="controls">
						<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title', 'required'))?>
					</div>
				</div>

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
				
				<?if(count($locations) > 1 AND $form_show['location'] != FALSE):?>
                    <div class="control-group">
                        <?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location' ))?>
                        <div class="controls">          
                            <select name="location" id="location" class="input-xlarge" required>
                            <option></option>
                            <?function lolo($item, $key,$locs){?>
                            <option value="<?=$key?>"><?=$locs[$key]['name']?></option>
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

				<div class="control-group">
					<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
					<div class="controls">
						<?= FORM::textarea('description', Request::current()->post('description'), array('class'=>'span6', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
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
				<?if($form_show['phone'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
					<div class="controls">
						<?= FORM::input('phone', Request::current()->post('phone'), array('class'=>'input-xlarge', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<?endif?>
				<?if($form_show['address'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
					<div class="controls">
						<?= FORM::input('address', Request::current()->post('address'), array('class'=>'input-xlarge', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<?endif?>
				<?if($form_show['price'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
					<div class="controls">
						<div class="input-prepend">
						<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => i18n::money_format(1), 'class' => 'input-large', 'id' => 'price', 'type'=>'text'))?>
						</div>
					</div>
				</div>
				<?endif?>
				<?if($form_show['website'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
					<div class="controls">
						<?= FORM::input('website', Request::current()->post('website'), array('placeholder' => __('Website'), 'class' => 'input-xlarge', 'id' => 'website'))?>
					</div>
				</div>
				<?endif?>
				<?if (!Auth::instance()->get_user()):?>
				<div class="control-group">
					<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<div class="controls">
						<?= FORM::input('name', Request::current()->post('name'), array('class'=>'input-xlarge', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', Request::current()->post('email'), array('class'=>'input-xlarge', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
					</div>
				</div>
				<?endif?>

				<?if(core::config('advertisement.tos') != ''):?>
				<div class="control-group">
					<div class="controls">
                        <label class="checkbox">
                          	<input type="checkbox" required name="tos" id="tos"/> 
							<a target="_blank" href="<?=Route::url('page', array('seotitle'=>core::config('advertisement.tos')))?>"> <?=__('Terms of service')?></a>
						</label>
					</div>
				</div>
				<?endif?>
				<?if ($form_show['captcha'] != FALSE):?>
				<div class="control-group">
					<div class="controls">
						Captcha*:<br />
						<?= captcha::image_tag('contact');?><br />
						<?= FORM::input('captcha', "", array('class' => 'input-xlarge', 'id' => 'captcha', 'required'))?>
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<?= FORM::button('submit', __('Publish new'), array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('post_new',array('controller'=>'new','action'=>'index'))))?>
					<p class="help-block"><?=__('User account will be created')?></p>
				</div>
			</fieldset>
		<?= FORM::close()?>

	</div>
	<!--/well-->
