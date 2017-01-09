<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <script type="text/javascript" 
    src="http://disqus.com/forums/<?=core::config('advertisement.disqus')?>/combination_widget.js?num_items=<?=$widget->comments_limit?>&hide_mods=0&default_tab=recent&excerpt_length=200">
    </script>
</div>