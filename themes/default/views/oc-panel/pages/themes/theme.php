<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Themes')?></h1>
    <p><?=__('You can change the look and feel of your website here.')?><a href="http://open-classifieds.com/2013/08/21/how-to-change-theme/" target="_blank"><?=__('Read more')?></a></p>
</div>
<!-- install theme form -->
<div class="well col-md-5 col-sm-10 col-xs-12">
    <span class="label label-info"><?=__('Install theme')?></span>
<?= FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'download')), array('class'=>'form-inline'))?>
    <p><?=__('Install theme from license.')?></p>
    
    <div class="form-group">
        <input type="text" name="license" id="licese" placeholder="<?=__('license')?>"/>
    </div>
        <?= FORM::button('submit', __('Download'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'theme','action'=>'download'))))?>

<?= FORM::close()?>

<?= FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme')), array('class'=>'form-inline', 'enctype'=>'multipart/form-data'))?>

    <p><?=__('To install new theme choose zip file.')?></p>
    <div class="form-group">
        <input type="file" name="theme_file" id="theme_file" />
    </div>
        <?= FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme'))))?>
<?= FORM::close()?>

</div>

<!-- end install themeform -->
<div class="media">
    <?if ($scr = Theme::get_theme_screenshot(Theme::$theme))?>
            <img class="media-object pull-left" width="150px" height="100px" src="<?=$scr?>">
    <div class="media-body">
        <h4 class="media-heading"><?=$selected['Name']?></h4>
        <p>
            <span class="badge badge-info"><?=__('Current Theme')?></span>
            <?if (Theme::has_options()):?>
            <a class="btn btn-xs btn-primary" title="<?=__('Theme Options')?>" 
                href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                <i class="glyphicon glyphicon-wrench"></i> </a>
            <?endif?>
        </p>
        <p><?=$selected['Description']?></p>
        <?if(Core::config('appearance.theme_mobile')!=''):?>
            <p>
                <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
                <a class="btn btn-xs btn-warning" title="<?=__('Disable')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
                    <i class="glyphicon glyphicon-remove"></i>
                </a>
                <a class="btn btn-xs btn-primary" title="<?=__('Options')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
                <i class="glyphicon glyphicon-wrench"></i></a>
            </p>
        <?endif?>
    </div>
</div>

<? if (count($themes)>1):?>
<div class="page-header">
    <h2><?=__('Available Themes')?></h2>
</div>
<div class="clearfix"></div>
<?$i=1; foreach ($themes as $theme=>$info):?>
    <?if(Theme::$theme!==$theme):?>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="thumbnail">

            <?if ($scr = Theme::get_theme_screenshot($theme)):?>
                <img width="300px" height="200px" src="<?=$scr?>">
            <?endif?>

            <div class="caption">
                <h3><?=$info['Name']?></h3>
                <p><?=$info['Description']?></p>
                <p><?=$info['License']?> v<?=$info['Version']?></p>
                <p>
                    <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                    <?if (Core::config('appearance.allow_query_theme')=='1'):?>
                    <a class="btn btn-default" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a> 
                    <?endif?>   
                </p>
            </div>
        </div>
    </div>
    <?if ($i%3==0):?><div class="clearfix"></div><?endif?>
    <?$i++;
    endif?>
<?endforeach?>

<?endif?>


<?
$a_m_themes = count($mobile_themes);
if(Core::config('appearance.theme_mobile')!='')
    $a_m_themes--;

if ($a_m_themes>0):?>
<h2><?=__('Available Mobile Themes')?></h2>
<div class="row-fluid">
<ul class="thumbnails">
<?$i=0;
foreach ($mobile_themes as $theme=>$info):?>
    <?if(Core::config('appearance.theme_mobile')!==$theme):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    <li class="col-md-4">
    <div class="thumbnail">

        <?if ($scr = Theme::get_theme_screenshot($theme)):?>
            <img width="300px" height="200px" src="<?=$scr?>">
        <?endif?>

        <div class="caption">
            <h3><?=$info['Name']?></h3>
            <p><?=$info['Description']?></p>
            <p><?=$info['License']?> v<?=$info['Version']?></p>
            <p>
                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                <a class="btn" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a>    
            </p>
        </div>
    </div>
    </li>
    <?$i++;
    endif?>
<?endforeach?>
</ul>
</div><!--/row-->    
<?endif?>

<? if (count($market)>0):?>
<h2><?=__('Themes Market')?></h2>
<p><?=__('Here you can find a selection of our premium themes.')?></p>
<p class="text-success"><?=__('All themes include support, updates and 1 site license.')?></p> <?=__('Also white labeled and free of ads')?>!

<?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    
<?endif?>