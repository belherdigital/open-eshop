<?php defined('SYSPATH') or die('No direct script access.');?>

    <div class="col-md-8">
        <div class="page-header">
            <h2><?=__('Available widgets')?></h2>
            <a href="http://open-classifieds.com/2013/08/26/overview-of-widgets/" target="_blank"><?=__('Read more')?></a>
        </div>
        <?$i=1?>
        <?foreach ($widgets as $widget):?>
            <?=$widget->form()?>
            <?if($i%3 == 0):?><div class="clearfix"></div><?endif?>
        <?$i++;endforeach?>
    </div><!--/span--> 
    
    <!--placeholders-->
    <div class="col-md-4">
        <?foreach ($placeholders as $placeholder=>$widgets):?>
        <div class="well sidebar-nav">
        <p class="nav-header"><?=$placeholder?></p>
            <ul class="nav nav-list plholder" id="<?=$placeholder?>" >
                <?foreach ($widgets as $widget):?>
                  <?=$widget->form()?>
                <?endforeach?>
            </ul>
        </div>
        <?endforeach?>
        <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'saveplaceholders'))?>'></span>
    </div>
    <!--placeholders-->


