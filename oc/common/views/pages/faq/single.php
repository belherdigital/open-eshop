<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$faq->title?></h1>
</div>

<div class="text-description">
	<?=Text::bb2html($faq->description,TRUE,FALSE)?>
</div><!-- /well -->

<?=$disqus?>