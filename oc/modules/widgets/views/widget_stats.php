<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->text_title?></h3>
<?if (!is_null($widget->info)):?>
<p><?=$widget->info->views?> <strong><?=__('views')?></strong></p>
<p><?=$widget->info->ads?> <strong><?=__('ads')?></strong></p>
<p><?=$widget->info->users?> <strong><?=__('users')?></strong></p>
<?endif?>
