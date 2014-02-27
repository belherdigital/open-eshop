<?php defined('SYSPATH') or die('No direct script access.');?>
<?if ($widget->ads_title!=''):?>
<h3><?=$widget->ads_title?></h3>
<?endif?>

<script type="text/javascript">
(function() {var uid = Math.round(Math.random()*10000);
document.write("<div id=\"serum_"+uid+"\" style=\"min-width:<?=$widget->width?>px;min-height:<?=$widget->height?>px;\" ></div>");
var as= document.createElement("script"); as.type  = "text/javascript"; as.async = true;
as.src= (document.location.protocol == "https:" ? "https" : "http")+ "://api.adserum.com/async.js?id="+uid+"&a=<?=$widget->id_publisher?>&f=<?=$widget->format?>&w=<?=$widget->width?>&h=<?=$widget->height?>";
var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(as, s);})();
</script>