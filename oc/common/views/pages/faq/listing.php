<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=_e('Frequently Asked Questions')?></h1>
</div>

<?if(count($faqs)):?>
<ol class="faq-list">
    <?foreach($faqs as $faq ):?>
    <li>
        <h4>
            <a title="<?=HTML::chars($faq->title)?>" href="<?=Route::url('faq', array('seotitle'=>$faq->seotitle))?>"> <?=$faq->title?></a>
        </h4>            
        <p><?=Text::limit_chars(Text::removebbcode($faq->description),400, NULL, TRUE);?>
            <a title="<?=HTML::chars($faq->title)?>" href="<?=Route::url('faq', array('seotitle'=>$faq->seotitle))?>"><?=_e('Read more')?>.</a>
        </p>
    </li>
    <?endforeach?>
</ol>
<?else:?>
<!-- Case when we dont have ads for specific category / location -->
    <div class="page-header">
       <h3><?=_e('We do not have any FAQ-s')?></h3>
    </div>
<?endif?>

<?=$disqus?>