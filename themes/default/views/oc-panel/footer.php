<?php defined('SYSPATH') or die('No direct script access.');?>
<hr>

<footer>
<!--This is the license for Open eShop, do not remove -->
<p>&copy;
<?if (Theme::get('premium')!=1):?>
    Web Powered by <a href="http://open-eshop.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script to sell digital goods Software">Open eShop</a> 
    2013
<?else:?>
    <?=core::config('general.site_name')?> <?=date('Y')?>
<?endif?>    

</p>
</footer>