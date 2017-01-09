<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Breadcrumbs
 *
 * @author Kieran Graham
 */
?>
<? if (count($breadcrumbs) > 0) : ?>
<ul id="breadcrumbs">
<? foreach ($breadcrumbs as $crumb) : ?>
<? if ($crumb->get_url() !== NULL) :  ?>
	<li><a href="<?=$crumb->get_url()?>"><?=$crumb->get_title()?></a></li>
<? else : ?>
	<li><?=$crumb->get_title()?></li>
<? endif; ?>
<? endforeach; ?>
</ul>
<? endif; ?>