<?php defined('SYSPATH') or die('No direct script access.');?>

<h1 class="page-header page-title">
    <?=__('Available widgets')?>
    <a target="_blank" href="https://docs.yclas.com/overview-of-widgets/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <?$i=1?>
            <?foreach ($widgets as $widget):?>
                <?=$widget->form()?>
                <?if($i%3 == 0):?><div class="clearfix"></div><?endif?>
            <?$i++;endforeach?>
        </div>
    </div><!--/span--> 
    
    <!--placeholders-->
    <div class="col-md-4">
        <?foreach ($placeholders as $placeholder=>$widgets):?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?=$placeholder?></div>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-list plholder" id="<?=$placeholder?>" >
                        <?foreach ($widgets as $widget):?>
                          <?=$widget->form()?>
                        <?endforeach?>
                    </ul>
                </div>
            </div>
        <?endforeach?>
        <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'saveplaceholders'))?>'></span>
    </div>
    <!--placeholders-->
</div>