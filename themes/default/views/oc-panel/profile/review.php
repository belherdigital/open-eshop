<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=__("Review").' '.$product->title?></h1>
</div>

<div class="well">
<?if ($review->loaded()):?>
    <div id="rated_raty" data-score="<?=$review->rate?>" ></div>
    <blockquote><?=Text::bb2html($review->description,TRUE)?></blockquote>
<?else:?>
    <?php if ($errors): ?>
    <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
    <ul class="errors">
    <?php foreach ($errors as $message): ?>
        <li><?php echo $message ?></li>
    <?php endforeach ?>
    </ul>
    <?php endif ?>       
    <?=FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'review','id'=>$product->id_product)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
    <fieldset>

        <div id="review_raty"></div>

        <div class="control-group">
            <?= FORM::label('description', __('Review'), array('class'=>'control-label', 'for'=>'description'))?>
            <div class="controls">
                <?= FORM::textarea('description', core::post('description',__('Review')), array('placeholder' => __('Review'), 'class' => 'span6', 'name'=>'description', 'id'=>'description', 'required'))?>   
            </div>
        </div>
    
        <div class="control-group">
            <div class="controls">
                <?= FORM::button('submit', __('Publish new review'), array('type'=>'submit', 'class'=>'btn btn-success', 
                'action'=>Route::url('oc-panel',array('controller'=>'profile','action'=>'review','id'=>$product->id_product))) )?>
            </div>
            <br class="clear">
        </div>
    </fieldset>
    <?= FORM::close()?>
<?endif?>
</div><!--end span10-->