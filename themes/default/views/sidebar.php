<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3"> 
<?foreach ( widgets::get('sidebar') as $widget):?>
    <?if(get_class($widget) != 'Widget_Contact' ):?>
        <div class="category_box_title custom_box">
        </div>
        <div class="well custom_box_content" >
            <?=$widget->render()?>
        </div>
   <?else:?>
        <?if(Request::current()->controller()=='ad' AND Request::current()->action()=='view'):?>
            <div class="category_box_title custom_box">
            </div>
            <div class="well custom_box_content" >
                <?=$widget->render()?>
            </div>
        <?endif?>
    <?endif?>
<?endforeach?>

<?if (Theme::get('premium')!=1):?>
    <div class="custom_box_content" >
    <script type="text/javascript">if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
        document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.com/sync.js?a=6&f=3&w=200&h=200\"></scr"+"ipt>");
    </script>
    </div>
<?endif?>
</div>
<!--/Sidebar--> 