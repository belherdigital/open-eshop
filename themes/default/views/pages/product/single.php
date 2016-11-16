<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="col-md-6">
	<?if($images):?>
		<div class="carousel slide article-slide" id="article-photo-carousel">
		  	<!-- Wrapper for slides -->
		  	<div class="carousel-inner cont-slider">
			    <?$i=0;
	            foreach ($images as $path => $value):?>
	                <?if($images = $product->get_images()):?>
                        <?if( isset($value['thumb']) AND isset($value['image']) ):?>
	                        <div class="item <?=($i == 0)?'active':''?>">
		                        <a rel="prettyPhoto[gallery]" href="<?=$value['base'].$value['image']?>">
		                            <?=HTML::picture($value['base'].$value['image'], ['w' => 318, 'h' => 300], ['1200px' => ['w' => '318', 'h' => '300'], '992px' => ['w' => '440', 'h' => '300'], '768' => ['w' => '910', 'h' => '300']], ['alt' => HTML::chars($product->title).$i, 'class' => 'main-image'])?>
		                        </a>
	                        </div>               
                        <?endif?>   
	                <?endif?>
	            <?$i++;
	            endforeach?>
		  	</div>
		  	<!-- Indicators -->
		  	<ol class="carousel-indicators">
		  		<?$j=0;
		        foreach ($images as $path => $value):?>
			        <li class="<?=($j == 0)?'active':'item'?>" data-slide-to="<?=$j?>" data-target="#article-photo-carousel">
			            <?if($images = $product->get_images()):?>        
			                <?if( isset($value['thumb']) AND isset($value['image']) ):?>
			                    <img src="<?=Core::imagefly($value['base'].$value['thumb'],100,54)?>" alt="<?=HTML::chars($product->title)?> <?=$j?>">
			                <?endif?>   
			            <?endif?>
			        </li>
		        <?$j++;
		        endforeach?>
		  	</ol>
		</div>
	<?else:?>
		<img src="//www.placehold.it/300x300&text=<?=urlencode(__('No Image'))?>" width="300" height="300" alt="<?=HTML::chars($product->title)?> <?=__('No Image')?>">
	<?endif?>
</div>

<div class="col-md-6">

    <div class="page-header">
        <h1 class="single-h1"><?=$product->title?>
        <?if ($product->rate!==NULL):?>
            <div class="rating" itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">
                <meta itemprop="value" content="<?=$product->rate?>" >
                <meta itemprop="best"  content="<?=Model_Review::RATE_MAX?>" />
                <a class="" href="<?=Route::url('product-review', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" >
                    <?for ($i=0; $i < round($product->rate); $i++):?>
                        <span class="glyphicon glyphicon-star"></span>
                    <?endfor?>
                </a>
            </div>
       <?endif?>
       <?if (Auth::instance()->logged_in()):?>
       <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
            <a title="<?=__('Edit')?>" class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=> 'product', 'action'=>'update','id'=>$product->id_product))?>">
                <i class="glyphicon glyphicon-edit"></i>
            </a>
       <?endif?>
       <?endif?>
   </h1>
    </div>

    <?if (!empty($product->url_demo)):?>
        <?if (($total_skins = count($skins)) > 0 AND Theme::get('premium')==1):?>
            <div class="btn-group pull-right">
                <a class="btn btn-warning btn-xs" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"><?=__('Demo')?></a>
                <button class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu <?=($total_skins > 10) ? 'multi-column-dropdown' : NULL?>" id="menu_type">
                    <?foreach ($skins as $s):?>
                        <li><a title="<?=HTML::chars($s)?>" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>?skin=<?=$s?>"><?=$s?></a></li>
                    <?endforeach?>
                </ul>
            </div>
        <?else:?>
            <a class="btn btn-warning btn-xs pull-right" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" >
            <i class="glyphicon glyphicon-eye-open"></i> <?=__('Demo')?></a>
        <?endif?>
    <?endif?>

	<?if ($product->has_offer()):?>
	    <span class="offer">
	    	<h4><span class="label label-success">
	    		<i class="glyphicon glyphicon-bullhorn"></i>
	    	</span> <?=__('Offer')?> <?=$product->formated_price()?> 
	    	<del><?=$product->price.' '.$product->currency?></del> </h4>
	    </span>
		<span class="offer-valid"><?=__('Offer valid until')?> <?=(Date::format((Model_Coupon::current()->loaded())?Model_Coupon::current()->valid_date:$product->offer_valid))?></span>
	<?else:?>
	    <?if($product->final_price() != 0):?>
	        <h4><?=__('Price')?> : <span data-locale="<?=$product->currency?>" class="price-curry curry"><?=$product->formated_price()?></span></h4>
	    <?else:?>
	        <h4><?=__('Free')?></h4>
	    <?endif?>
	<?endif?>

	<div class="button-space-review">
        <div class="clearfix"></div><br>
        <?=View::factory('pages/product/buy-button',array('product'=>$product))?>
	</div>

</div>

<div class="col-md-12">
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
			<?if(core::config('product.number_of_orders')):?>
				<p><span class="glyphicon glyphicon-shopping-cart"></span> <?=$number_orders?></p>
			<?endif?>

            <?if(core::config('product.count_visits')==1):?>
			     <p><?=__('Hits')?> : <?=$hits?></p>
            <?endif?>

		    <?if ($product->has_file()==TRUE):?>
			    <p><?=__('Product format')?> : <?=mb_strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> </p>
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
</div>
<div class="clearfix"></div>
<br/>
<div class="coupon">
<?=View::factory('coupon')?>
</div>
<div class="clearfix"></div><br>
<?=$product->qr()?>
<?=$product->related()?>
<?=$product->disqus()?>