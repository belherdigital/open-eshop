<?php defined('SYSPATH') or die('No direct script access.');?>


 <?=Form::errors()?>
<div class="page-header">
	<h1><?=__('General Configuration')?></h1>
    <p class="">
        <?=__('General site settings.')?>
        <a class="btn btn-default pull-right" href="<?=Route::url('oc-panel',array('controller'=>'config'))?>"><?=__('All configurations')?></a>

    </p>
</div>

<div class="well">
<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general')), array('class'=>'form-horizontal config', 'enctype'=>'multipart/form-data'))?>
	<fieldset>
		
		
        <div class="form-group">
            <?= FORM::label($forms['maintenance']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/10/11/activate-access-terms-alert/'>".__("Maintenance Mode")."</a>", array('class'=>'col-md-3 control-label', 'for'=>$forms['maintenance']['key']))?>
            <div class="col-md-5">
	            	<div class="onoffswitch">
	            		<?= FORM::hidden($forms['maintenance']['key'], 0);?>
	            		<?= FORM::checkbox($forms['maintenance']['key'], 1, (bool) $forms['maintenance']['value'], array(
	            		'placeholder' => "TRUE or FALSE", 
	            		'class' => 'onoffswitch-checkbox', 
	            		'id' => $forms['maintenance']['key'], 
	                'data-content'=> __("Enables the site to maintenance"),
	            		'data-trigger'=>"hover",
	            		'data-placement'=>"right",
	            		'data-toggle'=>"popover",
	                'data-original-title'=>__("Maintenance Mode"),
	            		))?>
	            		<?= FORM::label($forms['maintenance']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['maintenance']['key']))?>
	            	</div>
            </div>
		</div>

		<div class="form-group">
	        	<?= FORM::label($forms['disallowbots']['key'], __("Disallows Robots on this website"), array('class'=>'control-label col-sm-3', 'for'=>$forms['disallowbots']['key']))?>
	        	<div class="col-sm-4">
	        		<div class="onoffswitch">
	        			<?= Form::checkbox($forms['disallowbots']['key'], 1, (bool) $forms['disallowbots']['value'], array(
	        			'placeholder' => __("TRUE or FALSE"), 
	        			'class' => 'onoffswitch-checkbox', 
	        			'id' => $forms['disallowbots']['key'], 
	        			'data-content'=> __("Disallows (blocks) Bots and Crawlers on the website"),
	        			'data-trigger'=>"hover",
	        			'data-placement'=>"right",
	        			'data-toggle'=>"popover",
	        			'data-original-title'=>__("Disallows (blocks) Bots and Crawlers on the website"),
	        			))?>
	        			<?= FORM::label($forms['disallowbots']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['maintenance']['key']))?>
	        		</div>
	        	</div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['cookie_consent']['key'], __("Cookie consent"), array('class'=>'control-label col-sm-3', 'for'=>$forms['cookie_consent']['key']))?>
            <div class="col-sm-4">
                <div class="onoffswitch">
                    <?= Form::checkbox($forms['cookie_consent']['key'], 1, (bool) $forms['cookie_consent']['value'], array(
                    'placeholder' => __("TRUE or FALSE"), 
                    'class' => 'onoffswitch-checkbox', 
                    'id' => $forms['cookie_consent']['key'], 
                    'data-content'=> __("Enables an alert to accept cookies"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Cookie consent"),
                    ))?>
                    <?= FORM::label($forms['cookie_consent']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['cookie_consent']['key']))?>
                </div>
            </div>
        </div>
		
		<div class="form-group">
			<label class="control-label col-sm-3"><?=__("Time Zone")?>:</label>                
            <div class="col-sm-5">
            <?= FORM::select($i18n['timezone']['key'], Date::get_timezones(), core::request('TIMEZONE',date_default_timezone_get()), array(
					'placeholder' => "Madrid [+1:00]", 
					'class' => 'tips form-control', 
					'id' => $i18n['timezone']['key'], 
					))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['landing_page']['key'], __('Landing page'), array('class'=>'control-label col-sm-3', 'for'=>$forms['landing_page']['key']))?>
            <div class="col-md-5">
                <?= FORM::select($forms['landing_page']['key'], array('{"controller":"home","action":"index"}'=>'HOME','{"controller":"product","action":"listing"}'=>'LISTING'), $forms['landing_page']['value'], array(
                'class' => 'tips form-control input-sm', 
                'id' => $forms['landing_page']['key'], 
                'data-content'=> __("It changes landing page of website"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Landing page"),
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['site_name']['key'], __('Site name'), array('class'=>'col-md-3 control-label', 'for'=>$forms['site_name']['key']))?>
            <div class="col-md-5">
                <?= FORM::input($forms['site_name']['key'], $forms['site_name']['value'], array(
                'placeholder' => 'Open-classifieds', 
                'class' => 'tips form-control', 
                'id' => $forms['site_name']['key'],
                'data-content'=> __("Here you can declare your display name. This is seen by everyone!"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Site Name"), 
                'data-rule-required'=>'true',
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['company_name']['key'], __('Company Name'), array('class'=>'col-md-3 control-label', 'for'=>$forms['company_name']['key']))?>
            <div class="col-md-5">
                <?= FORM::input($forms['company_name']['key'], $forms['company_name']['value'], array(
                'placeholder' => 'Company LTD', 
                'class' => 'tips form-control', 
                'id' => $forms['vat_number']['key'],
                'data-content'=> __("Company Name"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Company name"), 
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['vat_number']['key'], __('VAT Number'), array('class'=>'col-md-3 control-label', 'for'=>$forms['vat_number']['key']))?>
            <div class="col-md-5">
                <?= FORM::input($forms['vat_number']['key'], $forms['vat_number']['value'], array(
                'placeholder' => 'VAT-XXXXX-XXX', 
                'class' => 'tips form-control', 
                'id' => $forms['vat_number']['key'],
                'data-content'=> __("Your VAT Number"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("VAT Number"), 
                ))?> 
            </div>
        </div>

        <div class="form-group">
                <?= FORM::label($forms['eu_vat']['key'], __("EU VAT"), array('class'=>'control-label col-sm-3', 'for'=>$forms['eu_vat']['key']))?>
                <div class="col-sm-4">
                    <div class="onoffswitch">
                        <?= Form::checkbox($forms['eu_vat']['key'], 1, (bool) $forms['eu_vat']['value'], array(
                        'placeholder' => __("TRUE or FALSE"), 
                        'class' => 'onoffswitch-checkbox', 
                        'id' => $forms['eu_vat']['key'], 
                        'data-content'=> __("Will calculate VAT based on EU country."),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>__("Will calculate VAT based on EU country."),
                        ))?>
                        <?= FORM::label($forms['eu_vat']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['maintenance']['key']))?>
                    </div>
                </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['vat_excluded_countries']['key'], __('VAT Excluded Countries'), array('class'=>'control-label col-sm-3', 'for'=>$forms['vat_excluded_countries']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms['vat_excluded_countries']['key'], $forms['vat_excluded_countries']['value'], array(
                'placeholder' => __('For banned VAT push enter.'), 
                'class' => 'tips form-control', 
                'id' => $forms['vat_excluded_countries']['key'], 
                'data-original-title'=> __("VAT Excluded Countries"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-role'=>'tagsinput',
                'data-content'=>__("Enter country codes to disable VAT charging"),
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['site_description']['key'], __('Site description'), array('class'=>'col-md-3 control-label', 'for'=>$forms['site_description']['key']))?>
            <div class="col-md-5">
                <?= FORM::input($forms['site_description']['key'], $forms['site_description']['value'], array(
                'placeholder' => '', 
                'class' => 'tips form-control', 
                'id' => $forms['site_description']['key'],
                ))?> 
            </div>
        </div>

		<?=FORM::hidden($forms['base_url']['key'], $forms['base_url']['value'])?>
		
		<div class="form-group">
			<?= FORM::label($forms['products_per_page']['key'], __('Products per page'), array('class'=>'col-md-3 control-label', 'for'=>$forms['products_per_page']['key']))?>
			<div class="col-md-5">
				<?= FORM::input($forms['products_per_page']['key'], $forms['products_per_page']['value'], array(
				'placeholder' => "20", 
				'class' => 'tips form-control', 
				'id' => $forms['products_per_page']['key'], 
				'data-content'=> __("This is to control how many products are being displayed per page. Insert an integer value, as a number limit."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Number of products per page"),
				'data-rule-required'=>'true',
				'data-rule-digits' => 'true',
				))?> 
			</div>
		</div>
		<div class="form-group">		
                <?= FORM::label($forms['number_format']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/08/06/how-to-currency-format/'>".__('Money format')."</a>", array('class'=>'col-md-3 control-label','for'=>$forms['number_format']['key']))?>
			<div class="col-md-5">
				<?= FORM::input($forms['number_format']['key'], $forms['number_format']['value'], array(
				'placeholder' => "20", 
				'class' => 'tips form-control', 
				'id' => $forms['number_format']['key'],
				'data-content'=> __("Number format is how you want to display numbers related to advertisements. More specific advertisement price. Every country have a specific way of dealing with decimal digits."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Decimal representation"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
           <?= FORM::label($forms['feed_elements']['key'], __('Products in RSS'), array('class'=>'col-md-3 control-label', 'for'=>$forms['feed_elements']['key']))?>
           <div class="col-md-5">
               <?= FORM::input($forms['feed_elements']['key'], $forms['feed_elements']['value'], array(
               'placeholder' => "20", 
               'class' => 'tips form-control', 
               'id' => $forms['feed_elements']['key'], 
               'data-content'=> __("Number of Ads"),
               'data-trigger'=>"hover",
               'data-placement'=>"right",
               'data-toggle'=>"popover",
               'data-original-title'=>__("How many products are going to appear in the RSS of your site."),
               'data-rule-required'=>'true',
               'data-rule-digits' => 'true',
               ))?> 
           </div>
       	</div>
		<div class="form-group">
			<?= FORM::label($forms['date_format']['key'], __('Date format'), array('class'=>'col-md-3 control-label', 'for'=>$forms['date_format']['key']))?>
			<div class="col-md-5">
				<?= FORM::input($forms['date_format']['key'], $forms['date_format']['value'], array(
				'placeholder' => "d/m/Y", 
				'class' => 'tips form-control', 
				'id' => $forms['date_format']['key'], 
				'data-content'=> __("Each product have a publish date. By selecting format, you can change how it is shown on your website."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Date format"),
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms['analytics']['key'], __('Analytics'), array('class'=>'col-md-3 control-label', 'for'=>$forms['analytics']['key']))?>
			<div class="col-md-5">
				<?= FORM::input($forms['analytics']['key'], $forms['analytics']['value'], array(
				'placeholder' => 'UA-XXXXX', 
				'class' => 'tips form-control', 
				'id' => $forms['analytics']['key'],
				'data-content'=> __(""),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Analytics"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['allowed_formats']['key'], __('Allowed image formats'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['allowed_formats']['key']))?>
			<div class="col-md-5">
				<?= FORM::select("allowed_formats[]", array('jpeg'=>'jpeg','jpg'=>'jpg','png'=>'png','raw'=>'raw','gif'=>'gif'), explode(',', $forms_img['allowed_formats']['value']), array(
				'placeholder' => $forms_img['allowed_formats']['value'],
				'multiple' => 'true',
				'class' => 'tips form-control', 
				'id' => $forms_img['allowed_formats']['key'],
				'data-content'=> __("Set this up to restrict image formats that are being uploaded to your server."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Allowed image formats"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['max_image_size']['key'], __('Max image size'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['max_image_size']['key']))?>
			<div class="col-md-5">
				<div class="input-group">
					<?= FORM::input($forms_img['max_image_size']['key'], $forms_img['max_image_size']['value'], array(
					'placeholder' => "5", 
					'class' => 'tips form-control', 
					'id' => $forms_img['max_image_size']['key'],
					'data-content'=> __("Control the size of images being uploaded. Enter an integer value to set maximum image size in mega bites(Mb)."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image size in mega bites(Mb)"), 
					'data-rule-required'=>'true',
					'data-rule-digits' => 'true',
					))?>
					<span class="input-group-addon">MB</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['height']['key'], __('Image height'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['height']['key']))?>
			<div class="col-md-5">
				<div class="input-group">
					<?= FORM::input($forms_img['height']['key'], $forms_img['height']['value'], array(
					'placeholder' => "700", 
					'class' => 'tips form-control', 
					'id' => $forms_img['height']['key'], 
					'data-content'=> __("Each image is resized when uploaded. This is the height of big image. Note: you can leave this field blank to set AUTO height resize."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image height in pixels(px)"),
					'data-rule-digits' => 'true',
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['width']['key'], __('Image width'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['width']['key']))?>
			<div class="col-md-5">
				<div class="input-group">
					<?= FORM::input($forms_img['width']['key'], $forms_img['width']['value'], array(
					'placeholder' => "1024", 
					'class' => 'tips form-control', 
					'id' => $forms_img['width']['key'],
					'data-content'=> __("Each image is resized when uploaded. This is the width of big image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image width in pixels(px)"), 
					'data-rule-required'=>'true',
					'data-rule-digits' => 'true',
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['height_thumb']['key'], __('Thumb height'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['height_thumb']['key']))?>
			<div class="col-md-5">
				<div class="input-group">
					<?= FORM::input($forms_img['height_thumb']['key'], $forms_img['height_thumb']['value'], array(
					'placeholder' => "200", 
					'class' => 'tips form-control', 
					'id' => $forms_img['height_thumb']['key'],
					'data-content'=> __("Thumb is a small image resized to fit certain elements. This is height of this image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb height in pixels(px)"), 
					'data-rule-required'=>'true',
					'data-rule-digits' => 'true',
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['width_thumb']['key'], __('Thumb width'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['width_thumb']['key']))?>
			<div class="col-md-5">
				<div class="input-group">
					<?= FORM::input($forms_img['width_thumb']['key'], $forms_img['width_thumb']['value'], array(
					'placeholder' => "200", 
					'class' => 'tips form-control', 
					'id' => $forms_img['width_thumb']['key'],
					'data-content'=> __("Thumb is a small image resized to fit certain elements. This is width of this image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb width in pixels(px)"),
					'data-rule-required'=>'true',
					'data-rule-digits' => 'true',
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
        <div class="form-group">
            <?= FORM::label($forms_img['quality']['key'], __('Image quality'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['quality']['key']))?>
            <div class="col-md-5">
                <div class="input-group">
                    <?= FORM::input($forms_img['quality']['key'], $forms_img['quality']['value'], array(
                    'placeholder' => "95", 
                    'class' => 'tips form-control', 
                    'id' => $forms_img['quality']['key'],
                    'data-content'=> __("Choose the quality of the stored images (1-100% of the original)."),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Image Quality"),
                    'data-rule-required'=>'true',
                    'data-rule-digits' => 'true',
                    ))?>
                    <span class="input-group-addon">%</span>
                </div> 
            </div>
        </div>
        
        <div class="form-group">
            <?= FORM::label($forms_img['watermark']['key'], __('Watermark'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['watermark']['key']))?>
            <div class="col-md-5">
            	<div class="onoffswitch">
            		<?= FORM::hidden($forms_img['watermark']['key'], 0);?>
            		<?= FORM::checkbox($forms_img['watermark']['key'], 1, (bool) $forms_img['watermark']['value'], array(
            		'placeholder' => "TRUE or FALSE", 
            		'class' => 'onoffswitch-checkbox', 
            		'id' => $forms_img['watermark']['key'], 
            		'data-content'=> __("Adds a watermark to images"),
            		'data-trigger'=>"hover",
            		'data-placement'=>"right",
            		'data-toggle'=>"popover",
                'data-original-title'=>__("Watermark"),
            		))?>
            		<?= FORM::label($forms_img['watermark']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms_img['watermark']['key']))?>
            	</div>
            </div>
        </div>
        
        <div class="form-group">
			<?= FORM::label($forms_img['watermark_path']['key'], __('Watermark path'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['watermark_path']['key']))?>
			<div class="col-md-5">
				<?= FORM::input($forms_img['watermark_path']['key'], $forms_img['watermark_path']['value'], array(
				'placeholder' => "images/watermark.png", 
				'class' => 'tips form-control', 
				'id' => $forms_img['watermark_path']['key'],
				'data-content'=> __(""),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Watermark path"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['watermark_position']['key'], __('Watermark position'), array('class'=>'col-md-3 control-label', 'for'=>$forms_img['watermark_position']['key']))?>
			<div class="col-md-5">
				<?= FORM::select($forms_img['watermark_position']['key'], array(0=>"Center",1=>"Bottom",2=>"Top"), $forms_img['watermark_position']['value'], array(
				'placeholder' => $forms_img['watermark_position']['value'], 
				'class' => 'tips form-control', 
				'id' => $forms_img['watermark_position']['key'],
				'data-content'=> __(""),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Watermark position"), 
				))?> 
			</div>
		</div>

		<div class="form-group">
			<?= FORM::label($forms['akismet_key']['key'], "<a target='_blank' href='http://akismet.com/'>".__('Akismet Key')."</a>", array('class'=>'col-md-3 control-label', 'for'=>$forms['akismet_key']['key']))?>
			<div class="col-md-5">
				<?= FORM::input($forms['akismet_key']['key'], $forms['akismet_key']['value'], array(
				'placeholder' => "", 
				'class' => 'tips form-control', 
				'id' => $forms['akismet_key']['key'],
				'data-content'=> __("Providing akismet key will activate this feature. This feature deals with spam posts and emails."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Akismet Key"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms['alert_terms']['key'], '<a target="_blank" href="http://open-classifieds.com/2013/10/14/activate-access-terms-alert/">'.__('Accept Terms Alert')."</a>", array('class'=>'col-md-3 control-label', 'for'=>$forms['alert_terms']['key']))?>
			<div class="col-md-5">
				<?= FORM::select($forms['alert_terms']['key'], $pages, $forms['alert_terms']['value'], array( 
				'class' => 'tips form-control', 
				'id' => $forms['alert_terms']['key'], 
				'data-content'=> __("If you choose to use alert terms, you can select page you want to render. And to edit content, select link 'Content' on your admin panel sidebar. Find page named <name_you_specified> click 'Edit'. In section 'Description' add content that suits you."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Accept Terms Alert"),
				))?> 
			</div>
		</div>

        <div class="form-group">
            <?= FORM::label($forms['blog']['key'], __("Activates Blog posting"), array('class'=>'col-md-3 control-label', 'for'=>$forms['blog']['key']))?>
            <div class="col-md-5">
            	<div class="onoffswitch">
            		<?= FORM::hidden($forms['blog']['key'], 0);?>
            		<?= FORM::checkbox($forms['blog']['key'], 1, (bool) $forms['blog']['value'], array(
            		'placeholder' => "TRUE or FALSE", 
            		'class' => 'onoffswitch-checkbox', 
            		'id' => $forms['blog']['key'], 
                'data-content'=> __("Once set to TRUE, enables blog posts"),
            		'data-trigger'=>"hover",
            		'data-placement'=>"right",
            		'data-toggle'=>"popover",
                'data-original-title'=>__("Activates Blog posting"),
            		))?>
            		<?= FORM::label($forms['blog']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['blog']['key']))?>
            	</div>
            </div>
        </div>
        <div class="form-group">
                <?= FORM::label($forms['blog_disqus']['key'], __('Disqus for blog'), array('class'=>'col-md-3 control-label', 'for'=>$forms['blog_disqus']['key']))?>
                <div class="col-md-5">
                    <?= FORM::input($forms['blog_disqus']['key'], $forms['blog_disqus']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips form-control', 
                    'id' => $forms['blog_disqus']['key'], 
                    'data-content'=> __("Disqus for Blog Comments"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("You need to write your disqus ID to enable the service."),
                    ))?> 
                </div>
            </div>
        <div class="form-group">
            <?= FORM::label($forms['faq']['key'], __("Activates FAQ"), array('class'=>'col-md-3 control-label', 'for'=>$forms['faq']['key']))?>
            <div class="col-md-5">
            	<div class="onoffswitch">
            		<?= FORM::hidden($forms['faq']['key'], 0);?>
            		<?= FORM::checkbox($forms['faq']['key'], 1, (bool) $forms['faq']['value'], array(
            		'placeholder' => "TRUE or FALSE", 
            		'class' => 'onoffswitch-checkbox', 
            		'id' => $forms['faq']['key'], 
                'data-content'=> __("Once set to TRUE, enables FAQ"),
            		'data-trigger'=>"hover",
            		'data-placement'=>"right",
            		'data-toggle'=>"popover",
                'data-original-title'=>__("Activates FAQ"),
            		))?>
            		<?= FORM::label($forms['faq']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['faq']['key']))?>
            	</div>
            </div>
        </div>
        <div class="form-group">
                <?= FORM::label($forms['faq_disqus']['key'], __('Disqus for FAQ'), array('class'=>'col-md-3 control-label', 'for'=>$forms['faq_disqus']['key']))?>
                <div class="col-md-5">
                    <?= FORM::input($forms['faq_disqus']['key'], $forms['faq_disqus']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips form-control', 
                    'id' => $forms['faq_disqus']['key'], 
                    'data-content'=> __("Disqus for FAQ Comments"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("You need to write your disqus ID to enable the service."),
                    ))?> 
                </div>
            </div>
        <div class="form-group">
            <?= FORM::label($forms['forums']['key'], __("Activates Forums"), array('class'=>'col-md-3 control-label', 'for'=>$forms['forums']['key']))?>
            <div class="col-md-5">
            	<div class="onoffswitch">
            		<?= FORM::hidden($forms['forums']['key'], 0);?>
            		<?= FORM::checkbox($forms['forums']['key'], 1, (bool) $forms['forums']['value'], array(
            		'placeholder' => "TRUE or FALSE", 
            		'class' => 'onoffswitch-checkbox', 
            		'id' => $forms['forums']['key'], 
                'data-content'=> __("Once set to TRUE, enables forums posts"),
            		'data-trigger'=>"hover",
            		'data-placement'=>"right",
            		'data-toggle'=>"popover",
                'data-original-title'=>__("Activates Forums"),
            		))?>
            		<?= FORM::label($forms['forums']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['forums']['key']))?>
            	</div>
            </div>
        </div>

		<div class="form-group">
			<?= FORM::label($forms['sort_by']['key'], __('Sort by in listing'), array('class'=>'control-label col-sm-3', 'for'=>$forms['sort_by']['key']))?>
			<div class="col-sm-4">
				<?= FORM::select($forms['sort_by']['key'], array(
					'title-asc'=>__("Name (A-Z)"),
					'title-desc'=>__("Name (Z-A)"),
					'price-asc'=>__("Price (Low)"),
					'price-desc'=>__("Price (High)"),
					'featured'=>__("Featured"),
					'published-asc'=>__("Newest"),
					'published-desc'=>__("Oldest"),
				),
				$forms['sort_by']['value'], array(
				'placeholder' => $forms['sort_by']['value'], 
				'class' => 'tips form-control input-sm ', 
				'id' => $forms['sort_by']['key'],
				'data-content'=> __("Sort by in listing"),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Sort by in listing"), 
				))?> 
			</div>
		</div>

        <div class="form-group">
            <?= FORM::label($forms['banned_words']['key'], __('Banned words'), array('class'=>'control-label col-sm-3', 'for'=>$forms['banned_words']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms['banned_words']['key'], $forms['banned_words']['value'], array(
                'placeholder' => __('For banned word push enter.'), 
                'class' => 'tips form-control', 
                'id' => $forms['banned_words']['key'], 
                'data-original-title'=> __("Banned words are separated with coma (,)"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-role'=>'tagsinput',
                'data-content'=>__("You need to write your banned words to enable the service."),
                ))?> 
            </div>

        </div>

        <div class="form-group">
            <?= FORM::label($forms['banned_words_replacement']['key'], __('Banned words replacement'), array('class'=>'control-label col-sm-3', 'for'=>$forms['banned_words_replacement']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms['banned_words_replacement']['key'], $forms['banned_words_replacement']['value'], array(
                'placeholder' => "xxx", 
                'class' => 'tips form-control', 
                'id' => $forms['banned_words_replacement']['key'], 
                'data-original-title'=> __("Replacement of a banned word"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-content'=>__("Banned word replacement replaces selected array with the string you provided."),
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['html_head']['key'], __('HTML in HEAD element'), array('class'=>'col-md-3 control-label', 'for'=>$forms['html_head']['key']))?>
            <div class="col-md-5">
                <?= FORM::textarea($forms['html_head']['key'], $forms['html_head']['value'], array(
                'placeholder' => '',
                'rows' => 3, 'cols' => 50, 
                'class' => 'tips form-control input-sm', 
                'id' => $forms['html_head']['key'],
                'data-content'=> __('To include your custom HTML code (validation metadata, reference to JS/CSS files, etc.) in the HEAD element of the rendered page.'),
                'data-trigger'=>"hover",
                'data-placement'=>"bottom",
                'data-toggle'=>"popover",
                'data-original-title'=>__('HTML in HEAD element'), 
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['html_footer']['key'], __('HTML in footer'), array('class'=>'col-md-3 control-label', 'for'=>$forms['html_footer']['key']))?>
            <div class="col-md-5">
                <?= FORM::textarea($forms['html_footer']['key'], $forms['html_footer']['value'], array(
                'placeholder' => '',
                'rows' => 3, 'cols' => 50, 
                'class' => 'tips form-control input-sm', 
                'id' => $forms['html_footer']['key'],
                'data-content'=> __('To include your custom HTML code (reference to JS or CSS files, etc.) in the footer of the rendered page.'),
                'data-trigger'=>"hover",
                'data-placement'=>"bottom",
                'data-toggle'=>"popover",
                'data-original-title'=>__('HTML in footer'), 
                ))?> 
            </div>
        </div>

        <h2><?=__("Amazon S3 Configuration")?></h2>
        
        <div class="form-group">
            <?= FORM::label($forms_img['aws_s3_active']['key'], __('Amazon S3 active'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['aws_s3_active']['key']))?>
            <div class="col-sm-4">
                <div class="onoffswitch">
                    <?= FORM::hidden($forms_img['aws_s3_active']['key'], 0);?>
                    <?= Form::checkbox($forms_img['aws_s3_active']['key'], 1, (bool) $forms_img['aws_s3_active']['value'], array(
                    'placeholder' => __("TRUE or FALSE"), 
                    'class' => 'onoffswitch-checkbox', 
                    'id' => $forms_img['aws_s3_active']['key'], 
                    'data-content'=> __('Amazon S3 active'),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>'',
                    ))?>
                    <?= FORM::label($forms_img['aws_s3_active']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms_img['aws_s3_active']['key']))?>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <?= FORM::label($forms_img['aws_access_key']['key'], __('AWS Access Key'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['aws_access_key']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms_img['aws_access_key']['key'], $forms_img['aws_access_key']['value'], array(
                'placeholder' => '', 
                'class' => 'tips form-control', 
                'id' => $forms_img['aws_access_key']['key'], 
                'data-content'=> __('AWS Access Key'),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>'',              
                ))?> 
            </div>
        </div>
        
        <div class="form-group">
            <?= FORM::label($forms_img['aws_secret_key']['key'], __('AWS Secret Key'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['aws_secret_key']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms_img['aws_secret_key']['key'], $forms_img['aws_secret_key']['value'], array(
                'placeholder' => "", 
                'class' => 'tips form-control', 
                'id' => $forms_img['aws_secret_key']['key'], 
                'data-content'=> __('AWS Secret Key'),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>'',          
                ))?> 
            </div>
        </div>
        
        <div class="form-group">
            <?= FORM::label($forms_img['aws_s3_bucket']['key'], __('Amazon S3 Bucket Name'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['aws_s3_bucket']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms_img['aws_s3_bucket']['key'], $forms_img['aws_s3_bucket']['value'], array(
                'placeholder' => "", 
                'class' => 'tips form-control', 
                'id' => $forms_img['aws_s3_bucket']['key'], 
                'data-content'=> __('Amazon S3 Bucket Name'),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>'',          
                ))?> 
            </div>
        </div>
        
        <?  if (($aws_s3_domain = strpos($forms_img['aws_s3_domain']['value'], 's3.amazonaws.com')) !== FALSE) 
                $forms_img['aws_s3_domain']['value'] = ($aws_s3_domain > 0) ? 'bn-s3' : 's3-bn';
        ?>
        <div class="form-group">
            <?= FORM::label($forms_img['aws_s3_domain']['key'], __('S3 Domain Name'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['aws_s3_domain']['key']))?>
            <div class="col-sm-4">
                <?= FORM::select($forms_img['aws_s3_domain']['key'], array( 'bn'    =>"bucket_name",
                                                                        'bn-s3' =>"bucket_name.s3.amazonaws.com",
                                                                        's3-bn' =>"s3.amazonaws.com/bucket_name"), 
                $forms_img['aws_s3_domain']['value'], array(
                'placeholder' => $forms_img['aws_s3_domain']['value'], 
                'class' => 'tips form-control input-sm ', 
                'id' => $forms_img['aws_s3_domain']['key'],
                'data-content'=> __("Amazon S3 Domain Name"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("S3 Domain Name"), 
                ))?> 
            </div>
        </div>

		<div class="form-actions">
			<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
		</div>
	</fieldset>	
</div><!--end well-->
