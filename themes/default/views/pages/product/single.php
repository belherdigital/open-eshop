<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="col-md-6">

<?$images = $product->get_images()?>
	<?if(isset($images[1]['image'])):?>
		<img class="main-image" src="<?=URL::base()?><?=$images[1]['image']?>">
	<?else:?>
		<img src="http://www.placehold.it/300x300&text=No Image">
	<?endif?>
    <?if($images):?>
		<div class="clearfix"></div><br>
	    <div id="slider-fixed-products" class="carousel slide">
	        <div class="carousel-inner">
	            <div class="active item">
	                <?$i=0;
	                foreach ($images as $path => $value):?>
	                <?if ($i%3==0 AND $i!=0):?></div><div class="item"><?endif?>
		                <?if($images = $product->get_images()):?>
						    <div class="picture">
						        <?if( isset($value['thumb']) AND isset($value['image']) ):?>
						            <a rel="prettyPhoto[gallery]" href="<?=URL::base()?><?= $value['image']?>">
						                <figure><img src="<?=URL::base()?><?= $value['thumb']?>" ></figure>
						            </a>
						        <?endif?>   
						    </div>
					    <?endif?>
	                <?$i++;
	                endforeach?>
	          	</div>
	        </div>
	        <?if($i > 4):?>
	        <a class="left carousel-control" href="#slider-fixed-products" data-slide="prev">
	            <span class="glyphicon glyphicon-chevron-left"></span>
	        </a>
	        <a class="right carousel-control" href="#slider-fixed-products" data-slide="next">
	            <span class="glyphicon glyphicon-chevron-right"></span>
	        </a>
	        <?endif?>
		</div>
	<?endif?>
</div>
<div class="col-md-6">
	<div class="page-header">
		<h3><?=$product->title?></h3>
		<?if ($product->rate!==NULL):?>
	    <a class="label label-warning" href="<?=Route::url('product-review', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" >
		   	<?for ($i=0; $i < round($product->rate,1); $i++):?>
		   		<span class="glyphicon glyphicon-star"></span>
		   	<?endfor?>
	    </a>
	<?endif?>
	</div>

	<?if ($product->has_offer()):?>
	    <span class="offer">
	    	<h4><span class="label label-success">
	    		<i class="glyphicon glyphicon-bullhorn"></i>
	    	</span> <?=__('Offer')?> <?=$product->final_price().' '.$product->currency?> 
	    	<del><?=$product->price.' '.$product->currency?></del> </h4>
	    </span>
		<span class="offer-valid"><?=__('Offer valid until')?> <?=Date::format($product->offer_valid)?></span>
	<?else:?>
	    <?if($product->final_price() != 0):?>
	        <h4><?=__('Price')?> : <?=$product->final_price().' '.$product->currency?></span></h4>
	    <?else:?>
	        <h4><?=__('Free')?></h4>
	    <?endif?>
	<?endif?>

	<?if (!empty($product->url_demo)):?>
	    <a class="btn btn-warning btn-xs pull-right" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" >
	        <i class="glyphicon glyphicon-eye-open"></i> <?=__('Demo')?></a>
	<?endif?>

	<div class="button-space">
	<?if ($product->final_price()>0):?>
		<div class="clearfix"></div><br>
	    <a class="btn btn-success pay-btn full-w" 
	        href="<?=Route::url('product-paypal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
	        <?=__('Pay with Paypal')?></a>

	    <?=$product->alternative_pay_button()?>
	    <?=StripeKO::button($product)?>
	    <?=Paymill::button($product)?>
	<?else:?>
	    <?if (!Auth::instance()->logged_in()):?>
	    <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
	        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
	    <?else:?>
	    <div class="clearfix"></div><br>
	    <a class="btn btn-info btn-large full-w"
	        href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'free','id'=>$product->seotitle))?>">
	    <?endif?>
	        <?if(!empty($product->file_name)):?>
	            <?=__('Free Download')?>
	        <?else:?>
	            <?=__('Get it for Free')?>
	        <?endif?>
	    </a>
	<?endif?>
		<div class="clearfix"></div><br>
	</div>
</div>

<div class="clearfix"></div><br>

	<ul class="nav nav-tabs mb-30">
	  	<li class="active">
	  		<a href="#description" data-toggle="tab"><?=__('Description')?></a>	
	  	</li>
	  	<li><a href="#details" data-toggle="tab"><?=__('Details')?></a></li>
	</ul>
	
	<div class="tab-content">
		<div class="tab-pane active" id="description">
			<?=Text::bb2html($product->description,TRUE)?>
		</div>
		<div class="tab-pane" id="details">
			<p><?=__('Hits')?> : <?=$hits?></p>

		    <?if (!empty($product->file_name)):?>
			    <p><?=__('Product format')?> : <?=strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> </p>
			    <p><?=__('Product size')?> : <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB</p>
		    <?endif?>

		    <?if ($product->support_days>0):?>
		    	<p><?=__('Professional support')?> : <?=$product->support_days?> <?=__('days')?></p>
		    <?endif?>

		    <?if ($product->licenses>0):?>
		    <p><?=__('Licenses')?> : <?=$product->licenses?>  
		        <?if ($product->license_days>0):?>
		        	<?=__('valid')?> <?=$product->license_days?> <?=__('days')?>
		        <?endif?>
		    </p>
		    <?endif?>
		</div>
	</div>
<br/>
<div class="coupon">
<?=View::factory('coupon')?>
</div>
<?=$product->related()?>
<?=$product->disqus()?>