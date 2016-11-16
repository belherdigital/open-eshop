<?php defined('SYSPATH') or die('No direct script access.');?>

    
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-4 col-md-4 section-box" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
                <img itemprop="photo" alt="<?=HTML::chars($product->title)?>" src="<?=URL::base()?><?=$product->get_first_image()?>" >
            </div>
            <div class="col-xs-8 col-md-8 section-box" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
                <h1 ><?=$product->title.' '.__("Reviews")?></h1>
                <meta itemprop="itemreviewed" content="<?=$product->title?>" >
                <hr />
                <div class="row rating-desc">
                    <div class="col-md-8">
                        <?for ($i=0; $i < round($product->rate); $i++):?>
                            <span class="glyphicon glyphicon-star"></span>
                        <?endfor?>
                        <span itemprop="average"><?=round($product->rate,1)?>/</span>
                        <span itemprop="best"><?=Model_Review::RATE_MAX?></span>
                        <span class="separator">|</span>
                        <span class="glyphicon glyphicon-comment"></span><span itemprop="count"><?=count($reviews)?></span> <?=__('reviews')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="button-space-review pull-left">
        <?=View::factory('pages/product/buy-button',array('product'=>$product))?>
    </div>

    <?if (!empty($product->url_demo)):?>
        <?if (count($skins)>0):?>
            <div class="btn-group pull-right">
                <a class="btn btn-warning btn-xs" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"><?=__('Demo')?></a>
                <button class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" id="menu_type">
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
    
    <div class="clearfix"></div>
    
<hgroup class="mb20"></hgroup>
<?if(count($reviews)):?>
    <?foreach ($reviews as $review):?>
    
    <article class="search-result row" itemscope itemtype="http://data-vocabulary.org/Review">
        <meta itemprop="itemreviewed" content="<?=$product->title?>" >

        <div class="col-xs-12 col-sm-12 col-md-3">
            <a title="<?=HTML::chars($review->user->name)?>" class="thumbnail"><img src="<?=$review->user->get_profile_image()?>" alt="<?=__('Profile image')?>" height="140"></a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-2">
            <ul class="meta-search">
                <li><i class="glyphicon glyphicon-calendar"></i>
                    <time itemprop="dtreviewed" datetime="<?=Date::format($review->created,'Y-m-d')?>">
                        <?=Date::format($review->created,Core::config('general.date_format'))?>
                    </time>
                </li>
                <li><i class="glyphicon glyphicon-time"></i> <span><?=Date::fuzzy_span(Date::mysql2unix($review->created))?></span></li>
                <li><i class="glyphicon glyphicon-user"></i> <span itemprop="reviewer"><?=$review->user->name?></span></li>
            <?if ($review->rate!==NULL):?>
        
            <div class="rating">
                <h1 class="rating-num" itemprop="rating"><?=round($review->rate,2)?>.0</h1>
                <?for ($i=0; $i < round($review->rate,1); $i++):?>
                    <span class="glyphicon glyphicon-star"></span>
                <?endfor?>
            </div>
            <?endif?>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-7">
            <p itemprop="description"><?=Text::bb2html($review->description,TRUE)?></p>                        
            <!-- <span class="plus"><a href="#" title="Lorem ipsum"><i class="glyphicon glyphicon-plus"></i></a></span> -->
        </div>
        <span class="clearfix borda"></span>
    </article>
    <hgroup class="mb20 mt20"></hgroup>
    <?endforeach?>

<?elseif (count($reviews) == 0):?>
<div class="page-header">
    <h3><?=__('We do not have any reviews for this product')?></h3>
</div>
<?endif?>