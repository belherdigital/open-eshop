<?php defined('SYSPATH') or die('No direct script access.');?>

	
<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Product Configuration')?></h1>
    <p class=""><?=__('List of optional fields. To activate/deactivate select "TRUE/FALSE" in desired field.')?></p>

</div>


<div class="well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'product')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
			<?foreach ($config as $c):?>
			<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
			<?endforeach?>

            <div class="control-group">
                <?= FORM::label($forms['products_in_home']['key'], __('Products in home'), array('class'=>'control-label', 'for'=>$forms['products_in_home']['key']))?>
                <div class="controls">
                    <?= FORM::select($forms['products_in_home']['key'], array(0=>__('Latest'),1=>__('Featured'),2=>__('Popular last month')), $forms['products_in_home']['value'], array(
                    'placeholder' => $forms['products_in_home']['value'], 
                    'class' => 'tips ', 
                    'id' => $forms['products_in_home']['key'],
                    'data-content'=> __("You can choose what products you want to display in home."),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Products in home"), 
                    ))?> 
                </div>
            </div>

			<div class="control-group">
				<?= FORM::label($forms['num_images']['key'], __('Number of images'), array('class'=>'control-label', 'for'=>$forms['num_images']['key']))?>
				<div class="controls">
					<?= FORM::input($forms['num_images']['key'], $forms['num_images']['value'], array(
					'placeholder' => "4", 
					'class' => 'tips', 
					'id' => $forms['num_images']['key'], 
					'data-content'=> __("Number of images"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Number of images displayed"),
					))?> 
				</div>
			</div>

            <div class="form-group">
                <?= FORM::label($forms['related']['key'], __('Related products'), array('class'=>'control-label col-sm-3', 'for'=>$forms['related']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::input($forms['related']['key'], $forms['related']['value'], array(
                    'placeholder' => $forms['related']['value'], 
                    'class' => 'tips form-control ', 
                    'id' => $forms['related']['key'],
                    'data-content'=> __("You can choose if theres random related products displayed at the prduct page"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Related products"), 
                    ))?> 
                </div>
            </div>

			<div class="control-group">
				<?= FORM::label($forms['max_size']['key'], __('Size of the file'), array('class'=>'control-label', 'for'=>$forms['max_size']['key']))?>
				<div class="controls">
					<?= FORM::input($forms['max_size']['key'], $forms['max_size']['value'], array(
					'placeholder' => "4", 
					'class' => 'tips', 
					'id' => $forms['max_size']['key'], 
					'data-content'=> __("Size of the file"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Size of the product file, limit on upload in MB"),
					))?> 
				</div>
			</div>

			<div class="control-group">
				<?= FORM::label($forms['formats']['key'], __('Allowed product formats'), array('class'=>'control-label', 'for'=>$forms['formats']['key']))?>
				<div class="controls">
					<?= FORM::select("formats[]", array("txt" => "txt", "doc" => "doc", "docx" => "docx", "pdf" => "pdf", 
														"tif" => "tif", "tiff" => "tiff", "gif" => "gif", "psd" => "psd", 
														"raw" => "raw", "wav" => "wav", "aif" => "aif", "mp3" => "mp3", "rm" => "rm ", 
														"ram" => "ram", "wma" => "wma", "ogg" => "ogg", "avi" => "avi", "wmv" => "wmv", 
														"mov" => "mov", "mp4" => "mp4", "jpeg" => "jpeg", "jpg" => "jpg", "png" => "png", 
														"zip" => "zip", "7z" => "7z ", "7zip" => "7zip", "rar" => "rar", "rar5" => "rar5", 
														"gzip" => "gzip" ), 
					explode(',', $forms['formats']['value']), array(
					'placeholder' => $forms['formats']['value'],
					'multiple' => 'true',
					'class' => 'tips', 
					'id' => $forms['formats']['key'],
					'data-content'=> __("Set this up to restrict product formats that are being uploaded to your server."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Allowed product formats"), 
					))?> 
				</div>
			</div>
            <div class="control-group">
                <?= FORM::label($forms['disqus']['key'], __('Disqus'), array('class'=>'control-label', 'for'=>$forms['disqus']['key']))?>
                <div class="controls">
                    <?= FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips', 
                    'id' => $forms['disqus']['key'], 
                    'data-content'=> __("Disqus Comments"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("You need to write your disqus ID to enable the service."),
                    ))?> 
                </div>
            </div>
			<div class="form-actions">
				<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'product'))))?>
			</div>
		</fieldset>
	<?= FORM::close()?>
</div><!--end well-->
