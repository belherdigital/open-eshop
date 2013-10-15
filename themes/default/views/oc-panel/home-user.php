<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="hero-unit">
    <h1>
    <?=__('Welcome')?>
    <?=Auth::instance()->get_user()->name?>
        &nbsp; <small><?=Auth::instance()->get_user()->email?> </small>
    </h1>

    <p><?=__('Thanks for using our website.')?> </a>
</div>



<?if (Theme::get('premium')!=1):?>
<script type="text/javascript">
if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.com/sync.js?a=6&f=8&w=728&h=90\"></scr"+"ipt>");
</script>
<?endif?>

