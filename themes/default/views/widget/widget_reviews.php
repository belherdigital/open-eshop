<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->review_title?></h3>
<ul>

<?foreach($widget->review as $review):?>

    <div class="media">
        <a class="pull-left">
            <img class="media-object dp img-circle" src="<?=$review->user->get_profile_image()?>" data-toggle="tooltip" data-placement="top" title="<?=HTML::chars($review->user->name)?>" width="30" height="30" style="width:30px;height:30px;" />
        </a>
        <div class="media-body">
            <h4 class="media-heading"><a href="<?=Route::url('product-review', array('seotitle'=>$review->product->seotitle,'category'=>$review->product->category->seoname))?>"><?=Text::limit_chars(Text::bb2html($review->product->title,TRUE),30, NULL, TRUE)?></a><span class="label label-warning"><?=$review->rate?></span></h4>
            <h5><?=Text::limit_chars(Text::bb2html($review->description,TRUE),30, NULL, TRUE)?></h5>
            <hr style="margin:8px auto">
        </div>
    </div>
<?endforeach?>
</ul>




        


