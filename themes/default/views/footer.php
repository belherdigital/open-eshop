<?php defined('SYSPATH') or die('No direct script access.');?>
<hr>

<footer>

<?foreach ( widgets::get('footer') as $widget):?>
<div class="span3">
    <?=$widget->render()?>
</div>
<?endforeach?>


<!--This is the license for Open eShop, do not remove -->
<p>&copy;
<?if (Theme::get('premium')!=1):?>
    Web Powered by <a href="http://open-eshop.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script to sell digital goods Classifieds Software">Open eShop</a> 
    2009 - <?=date('Y')?>
<?else:?>
    <?=core::config('general.site_name')?> <?=date('Y')?>
<?endif?>    


<?if(Core::config('appearance.theme_mobile')!=''):?>
- <a href="<?=Route::url('default')?>?theme=<?=Core::config('appearance.theme_mobile')?>"><?=__('Mobile Version')?></a>
<?endif?>
</p>
</footer>