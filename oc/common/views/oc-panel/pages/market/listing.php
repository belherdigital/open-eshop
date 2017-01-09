<?php defined('SYSPATH') or die('No direct script access.');?>

<? if (count($market)>=1):?>
<?$i=0;
foreach ($market as $item):?>
    <?if ($i%3==0):?><div class="clearfix"></div><?endif?>
    <div class="col-md-4 col-sm-4 theme">
    <div class="thumbnail <?if ( $item['price_offer']>0 AND strtotime($item['offer_valid'])>time()):?>alert-success<?endif?>" >

        <?if (empty($item['url_screenshot'])===FALSE):?>
            <img  class="thumb_market" src="<?=$item['url_screenshot']?>">
        <?else:?>
             <img class="thumb_market" src="//www.placehold.it/300x200&text=<?=$item['title']?>">
        <?endif?>   
        
        <div class="caption">
            <h3><?=$item['title']?></h3>
            <p>
                <?if ( $item['price_offer']>0 AND strtotime($item['offer_valid'])>time()):?>
                    <span class="label label-danger">$<?=$item['price_offer']?></span>
                    <span class="label label-info"><del>$<?=$item['price']?></del></span>
                <?else:?>
                    <span class="label label-info">$<?=$item['price']?></span>
                <?endif?>
                <span class="label label-success"><?=$item['type']?></span>
                <?if (empty($item['url_demo'])===FALSE):?>
                    <a class="btn btn-default btn-xs" target="_blank" href="<?=$item['url_demo']?>">
                        <i class="glyphicon  glyphicon-eye-open"></i>
                            <?=__('Preview')?>
                    </a>    
                <?endif?>
            </p>
            <p>
                <?=Text::bb2html($item['description'])?>
            </p>
            <?if ( $item['price_offer']>0 AND strtotime($item['offer_valid'])>time()):?>
            <p>
                <a href="<?=$item['url_buy']?>" class="btn btn-block btn-danger oe_button" data-toggle="modal" data-target="#marketModal"><?=__('Limited Offer!')?> $<?=$item['price_offer']?></a>
                <a href="<?=$item['url_buy']?>" class="btn btn-block btn-info oe_button" data-toggle="modal" data-target="#marketModal"><i class="glyphicon  glyphicon-time glyphicon"></i> <?=__('Valid Until')?>  <?= Date::format($item['offer_valid'], core::config('general.date_format'))?></a>
            </p>
            <?endif?>
            <p>
                <a class="btn btn-primary oe_button" data-toggle="modal" data-target="#marketModal" href="<?=$item['url_buy']?>">
                    <i class="glyphicon  glyphicon-shopping-cart"></i>  <?=__('Buy Now')?>
                </a>
            </p>
        </div>
    </div>
    </div>
    <?$i++;
    endforeach?>
<?endif?>

    <div class="col-md-4 col-sm-4 theme">
    <div class="thumbnail" >

        <img  class="thumb_market" src="https://open-classifieds.com/files/market/custom.png">
       
        
        <div class="caption">
            <h3>Custom Theme</h3>
            <p>
                <span class="label label-info">From $200</span>
                <span class="label label-success">themes</span>
            </p>

            <p>
                Want to make your classified ads site unique to look more professional for your customers? You can have a theme designed specially for you!
            </p>
            
            <p>
                <a class="btn btn-primary"  href="https://yclas.com/contact.html">
                    <i class="glyphicon  glyphicon-shopping-cart"></i>  Get a quote!
                </a>                
            </p>
        </div>
    </div>
    </div>