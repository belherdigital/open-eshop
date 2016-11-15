<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->products_title?></h3>

<div class="row">
	<div id="myCarousel" class="vertical-slider carousel vertical slide col-md-12" data-ride="carousel">
        <div class="col-md-6">
            <span data-slide="next" class="btn-vertical-slider glyphicon glyphicon-chevron-up "
                style="font-size: 30px"></span>  
        </div>
        <br />
        <!-- Carousel items -->
        <div class="carousel-inner">
	        <div class="item active" >
		        <?$i=0;foreach($widget->products as $product):?>
		            <?if($i == 1):?></div><div class="item"><?endif?>
		            <div class="pull-right">
		            	<?for ($j=0; $j < round($product->rate,1); $j++):?>
	                        <i class="glyphicon glyphicon-star"></i>
	                    <?endfor?>
	                </div>
	                <a href="<?=Route::url('product',array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"> 
		                <span class="wgt-rating-title"><?=$product->title?></span>
		                <?if($images = $product->get_images()):?>
		                	<img src="<?=Core::S3_domain().$images[1]['image']?>" class="thumbnail" alt="Image" />
		                <?endif?>
						
	                </a>
					
		        <?$i++;endforeach?>
		    </div>
        </div>
        <div class="col-md-6">
            <span data-slide="prev" class="btn-vertical-slider glyphicon glyphicon-chevron-down"
                style="color: Black; font-size: 30px"></span>
        </div>
    </div>
</div>

    


