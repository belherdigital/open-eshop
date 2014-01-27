<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="col-md-3 col-sm-12 col-xs-12"> 
<?foreach ( widgets::get('sidebar') as $widget):?>
    <?if(get_class($widget) != 'Widget_Contact'):?>
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

</div>