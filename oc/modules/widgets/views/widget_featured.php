<?php defined('SYSPATH') or die('No direct script access.');?>


<?foreach($widget->ads as $ad):?>
<div class="category_box_title custom_box ">
</div>
<div class="well <?=(get_class($widget)=='Widget_Featured')?'featured-custom-box':''?>" >
    <div class="featured-sidebar-box">
		<?if($ad->get_first_image() !== NULL):?>
            <div class="picture pull-right">
                <a class="pull-right" title="<?=$ad->title;?>" alt="<?=$ad->title;?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                <figure><img src="<?=URL::base('http')?><?=$ad->get_first_image()?>"></figure></a>
            </div>
        <?else:?>
            <div class="picture pull-right">
                <a class="pull-right" title="<?=$ad->title;?>" alt="<?=$ad->title;?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                <figure><img src="http://www.placehold.it/75x75&text=<?=$ad->category->name?>"  ></figure></a>
            </div>
        <?endif?>
	    <div class="featured-sidebar-box-header">
	        
	        <a href="<?=Route::url('ad',array('seotitle'=>$ad->seotitle,'category'=>$ad->category->seoname))?>" title="<?=$ad->title?>">
	        	<span class='f-box-header'><?=substr(Text::removebbcode($ad->title), 0, 15)?> </span>
	        </a>
	       
	    </div>
	    <div class="f-description">
	    	<p><?=substr(Text::removebbcode($ad->description), 0, 30)?></p>		
    	</div>
    </div>
</div>
<?endforeach?>
