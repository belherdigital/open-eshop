<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Product Configuration')?></h1>
    <p class=""><?=__('List of optional fields. To activate/deactivate select "TRUE/FALSE" in desired field.')?><a target="_blank" href="https://docs.open-eshop.com/product-configuration/" target="_blank"><?=__('Read more')?></a></p>

</div>

<div class="row">
    <div class="col-md-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'product')), array('class'=>'form-horizontal config', 'enctype'=>'multipart/form-data'))?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <?foreach ($config as $c):?>
                            <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                        <?endforeach?>
                        
                        <?
                            $products_in_home = array(0=>__('Latest'),
                                                        1=>__('Featured'),
                                                        2=>__('Popular last month'),
                                                        3=>__('Best rated'),
                                                        4=>__('None'));
                            if(core::config('product.count_visits')==0)
                                unset($products_in_home[2]);
                        ?>
                        <div class="form-group">
                            <?= FORM::label($forms['products_in_home']['key'], __('Products in home'), array('class'=>'col-md-4 control-label', 'for'=>$forms['products_in_home']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::select($forms['products_in_home']['key'], $products_in_home
                                , $forms['products_in_home']['value'], array(
                                'placeholder' => $forms['products_in_home']['value'], 
                                'class' => 'tips form-control', 
                                'id' => $forms['products_in_home']['key'],
                                'data-content'=> __("You can choose what products you want to display in home."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Products in home"), 
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['download_hours']['key'], __('Hours to download'), array('class'=>'col-md-4 control-label', 'for'=>$forms['download_hours']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::input($forms['download_hours']['key'], $forms['download_hours']['value'], array(
                                'placeholder' => "4", 
                                'class' => 'tips form-control', 
                                'id' => $forms['download_hours']['key'], 
                                'data-content'=> __("Hours between downloads, 0 = unlimited"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Hours between downloads"),
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true', 
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['download_times']['key'], __('Times to download'), array('class'=>'col-md-4 control-label', 'for'=>$forms['download_times']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::input($forms['download_times']['key'], $forms['download_times']['value'], array(
                                'placeholder' => "4", 
                                'class' => 'tips form-control', 
                                'id' => $forms['download_times']['key'], 
                                'data-content'=> __("Times the file can be downloaded, 0 = unlimited"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Times between downloads"),
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true', 
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['num_images']['key'], __('Number of images'), array('class'=>'col-md-4 control-label', 'for'=>$forms['num_images']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::input($forms['num_images']['key'], $forms['num_images']['value'], array(
                                'placeholder' => "4", 
                                'class' => 'tips form-control', 
                                'id' => $forms['num_images']['key'], 
                                'data-content'=> __("Number of images"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Number of images displayed"),
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true', 
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['related']['key'], __('Related products'), array('class'=>'col-md-4 control-label', 'for'=>$forms['related']['key']))?>
                            <div class="col-sm-8">
                                <?= FORM::input($forms['related']['key'], $forms['related']['value'], array(
                                'placeholder' => $forms['related']['value'], 
                                'class' => 'tips form-control ', 
                                'id' => $forms['related']['key'],
                                'data-content'=> __("You can choose if theres random related products displayed at the product page"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Related products"), 
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true', 
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['max_size']['key'], __('Size of the file'), array('class'=>'col-md-4 control-label', 'for'=>$forms['max_size']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::input($forms['max_size']['key'], $forms['max_size']['value'], array(
                                'placeholder' => "4", 
                                'class' => 'tips form-control', 
                                'id' => $forms['max_size']['key'], 
                                'data-content'=> __("Size of the file"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Size of the product file, limit on upload in MB"),
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true', 
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['formats']['key'], __('Allowed product formats'), array('class'=>'col-md-4 control-label', 'for'=>$forms['formats']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::select("formats[]", array("txt" => "txt", "doc" => "doc", "docx" => "docx", "pdf" => "pdf", 
                                                                    "tif" => "tif", "tiff" => "tiff", "gif" => "gif", "psd" => "psd", 
                                                                    "raw" => "raw", "wav" => "wav", "aif" => "aif", "mp3" => "mp3", "rm" => "rm ", 
                                                                    "ram" => "ram", "wma" => "wma", "ogg" => "ogg", "avi" => "avi", "wmv" => "wmv", 
                                                                    "mov" => "mov", "mp4" => "mp4", "mkv" => "mkv", "jpeg" => "jpeg", "jpg" => "jpg", "png" => "png", 
                                                                    "zip" => "zip", "7z" => "7z ", "7zip" => "7zip", "rar" => "rar", "rar5" => "rar5", 
                                                                    "gzip" => "gzip" ), 
                                explode(',', $forms['formats']['value']), array(
                                'placeholder' => $forms['formats']['value'],
                                'multiple' => 'true',
                                'class' => 'tips form-control', 
                                'id' => $forms['formats']['key'],
                                'data-content'=> __("Set this up to restrict product formats that are being uploaded to your server."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Allowed product formats"), 
                                ))?> 
                            </div>
                        </div>
                        <div class="form-group">
                            <?= FORM::label($forms['disqus']['key'], __('Disqus'), array('class'=>'col-md-4 control-label', 'for'=>$forms['disqus']['key']))?>
                            <div class="col-md-4">
                                <?= FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
                                'placeholder' => "", 
                                'class' => 'tips form-control', 
                                'id' => $forms['disqus']['key'], 
                                'data-content'=> __("Disqus Comments"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("You need to write your disqus ID to enable the service."),
                                ))?> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['count_visits']['key'], __('Count visits product'), array('class'=>'control-label col-sm-4', 'for'=>$forms['count_visits']['key']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= FORM::hidden($forms['count_visits']['key'], 0);?>
                                    <?= FORM::checkbox($forms['count_visits']['key'], 1, (bool) $forms['count_visits']['value'], array(
                                    'placeholder' => "", 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['count_visits']['key'], 
                                    'data-original-title'=> __("Count visits"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"right",
                                    'data-toggle'=>"popover",
                                    'data-content'=>__("You can choose if you wish to display amount of visits at each product."),
                                    ))?>
                                    <?= FORM::label($forms['count_visits']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['count_visits']['key']))?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['reviews']['key'], __("Product Reviews"), array('class'=>'col-md-4 control-label', 'for'=>$forms['reviews']['key']))?>
                            <div class="col-md-8">
                                <div class="onoffswitch">
                                    <?= FORM::hidden($forms['reviews']['key'], 0);?>
                                    <?= FORM::checkbox($forms['reviews']['key'], 1, (bool) $forms['reviews']['value'], array(
                                    'placeholder' => "TRUE or FALSE", 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['reviews']['key'], 
                                    'data-content'=> __("Enables users to review purchased products"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"right",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Product Reviews"),                   
                                    ))?>
                                    <?= FORM::label($forms['reviews']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['reviews']['key']))?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['demo_theme']['key'], __('Demo Bar Theme'), array('class'=>'col-md-4 control-label', 'for'=>$forms['demo_theme']['key']))?>
                            <div class="col-md-8">
                                <?= FORM::select($forms['demo_theme']['key'], array( 'amelia'    => 'Amelia',
                                                                        'cerulean'  => 'Cerulean',
                                                                        'cosmo'     => 'Cosmo',
                                                                        'cyborg'    => 'Cyborg',
                                                                        'journal'   => 'Journal',
                                                                        'flatly'    => 'Flatly',
                                                                        'readable'  => 'Readable',
                                                                        'simplex'   => 'Simplex',
                                                                        'slate'     => 'Slate',
                                                                        'spacelab'  => 'Space Lab',
                                                                        'united'    => 'United',
                                                                        'yeti'      => 'Yeti',
                                                                            ),  $forms['demo_theme']['value'], array(
                                'placeholder' => $forms['demo_theme']['value'], 
                                'class' => 'tips form-control', 
                                'id' => $forms['demo_theme']['key'],
                                'data-content'=> __("You can choose what theme to use in the demo bar."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Demo Bar Theme"), 
                                ))?> 
                            </div>
                        </div>
                        <div class="form-group">
                            <?= FORM::label($forms['demo_resize']['key'], __("Demo resize buttons"), array('class'=>'col-md-4 control-label', 'for'=>$forms['demo_resize']['key']))?>
                            <div class="col-md-8">
                                <div class="onoffswitch">
                                    <?= FORM::hidden($forms['demo_resize']['key'], 0);?>
                                    <?= FORM::checkbox($forms['demo_resize']['key'], 1, (bool) $forms['demo_resize']['value'], array(
                                    'placeholder' => "TRUE or FALSE", 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['demo_resize']['key'], 
                                    'data-content'=> __("Enables buttons to resize the demo"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"right",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Demo resize"),                    
                                    ))?>
                                    <?= FORM::label($forms['demo_resize']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['demo_resize']['key']))?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= FORM::label($forms['number_of_orders']['key'], __("Number of sales"), array('class'=>'col-md-4 control-label', 'for'=>$forms['number_of_orders']['key']))?>
                            <div class="col-md-8">
                                <div class="onoffswitch">
                                    <?= FORM::hidden($forms['number_of_orders']['key'], 0);?>
                                    <?= FORM::checkbox($forms['number_of_orders']['key'], 1, (bool) $forms['number_of_orders']['value'], array(
                                    'placeholder' => "TRUE or FALSE", 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['number_of_orders']['key'], 
                                    'data-content'=> __("Enables users to review purchased products"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"right",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Number of sales"),                     
                                    ))?>
                                    <?= FORM::label($forms['number_of_orders']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['number_of_orders']['key']))?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= FORM::label($forms['qr_code']['key'], __("Show QR code"), array('class'=>'col-md-4 control-label', 'for'=>$forms['qr_code']['key']))?>
                            <div class="col-md-8">
                                <div class="onoffswitch">
                                    <?= FORM::hidden($forms['qr_code']['key'], 0);?>
                                    <?= FORM::checkbox($forms['qr_code']['key'], 1, (bool) $forms['qr_code']['value'], array(
                                    'placeholder' => "TRUE or FALSE", 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['qr_code']['key'], 
                                    'data-content'=> __("Show QR code in Product"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"right",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Show QR code"),                    
                                    ))?>
                                    <?= FORM::label($forms['qr_code']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['qr_code']['key']))?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-8 col-sm-offset-4">
                        <?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'product'))))?>
                    </div>
                </div>
            </div>

        <?= FORM::close()?>
    </div>
</div>