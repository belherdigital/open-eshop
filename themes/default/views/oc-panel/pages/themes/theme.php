<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Themes')?></h1>
    <p><?=__('You can change the look and feel of your website here.')?><a href="http://open-classifieds.com/2013/08/21/how-to-change-theme/" target="_blank"><?=__('Read more')?></a></p>
</div>
<!-- install theme form -->
<?= FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
<div class="well pull-right">
    <span class="badge badge-info"><?=__('Install theme')?></span><p><?=__('To install new theme choose zip file.')?></p>
    
    <div class="controll-group">
        <input type="file" name="theme_file" id="theme_file" />
    </div>
    <div class="controll-group">
        <?= FORM::button('submit', __('Submit'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme'))))?>
    </div>
</div>
<?= FORM::close()?>
<!-- end install themeform -->
<div class="media">
    <?if ($scr = Theme::get_theme_screenshot(Theme::$theme))?>
            <img class="media-object pull-left" width="150px" height="100px" src="<?=$scr?>">
    <div class="media-body">
        <h4 class="media-heading"><?=$selected['Name']?></h4>
        <p>
            <span class="badge badge-info"><?=__('Current Theme')?></span>
            <?if (Theme::has_options()):?>
            <a class="btn btn-mini btn-primary" title="<?=__('Theme Options')?>" 
                href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                <i class="icon-wrench icon-white"></i> </a>
            <?endif?>
        </p>
        <p><?=$selected['Description']?></p>
        <?if(Core::config('appearance.theme_mobile')!=''):?>
            <p>
                <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
                <a class="btn btn-mini btn-warning" title="<?=__('Disable')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
                    <i class="icon-remove icon-white"></i>
                </a>
                <a class="btn btn-mini btn-primary" title="<?=__('Options')?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
                <i class="icon-wrench icon-white"></i></a>
            </p>
        <?endif?>
    </div>
</div>

<? if (count($themes)>1):?>
<h2><?=__('Available Themes')?></h2>
<div class="row-fluid">
<ul class="thumbnails">
<?$i=0;
foreach ($themes as $theme=>$info):?>
    <?if(Theme::$theme!==$theme):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    <li class="span4">
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
                <a class="btn" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a> 
                <?endif?>   
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
    <li class="span4">
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