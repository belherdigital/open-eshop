<?php defined('SYSPATH') or die('No direct script access.');?>
<?if ($widget->ads_title!=''):?>
<h3><?=$widget->ads_title?></h3>
<?endif?>

<script type="text/javascript">
if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.com/sync.js?a=<?=$widget->id_publisher?>&f=<?=$widget->format?>&w=<?=$widget->width?>&h=<?=$widget->height?>\"></scr"+"ipt>");
</script>