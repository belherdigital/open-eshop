<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div id="page-themes" class="page-header">
	<h1><?=__('Themes')?></h1>
    <p><?=__('You can change the look and feel of your website here.')?><a href="http://open-classifieds.com/2013/08/21/how-to-change-theme/" target="_blank"><?=__('Read more')?></a></p>
</div>
<!-- end install themeform -->
<div class="row">
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?if ($scr = Theme::get_theme_screenshot(Theme::$theme))?>
                <img class="media-object pull-left" width="150px" height="100px" src="<?=$scr?>">
                <div class="clearfix"></div>
                <div class="media-body">
                    <h4 class="media-heading"><?=$selected['Name']?></h4>
                    <p>
                        <span class="label label-info"><?=__('Current Theme')?></span>
                        <?if (Theme::has_options()):?>
                        <a class="btn btn-xs btn-primary ajax-load" title="<?=__('Theme Options')?>" 
                            href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                            <i class="glyphicon  glyphicon-wrench glyphicon"></i> </a>
                        <?endif?>
                    </p>
                    <p><?=$selected['Description']?></p>
                    <?if(Core::config('appearance.theme_mobile')!=''):?>
                        <p>
                            <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
                            <a class="btn btn-xs btn-warning" title="<?=__('Disable')?>" 
                                href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
                                <i class="glyphicon   glyphicon-remove"></i>
                            </a>
                            <a class="btn btn-xs btn-primary" title="<?=__('Options')?>" 
                                href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
                            <i class="glyphicon  glyphicon-wrench glyphicon"></i></a>
                        </p>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-header">
    <h2><?=__('Available Themes')?></h2>
</div>

<? if (count($themes)>1):?>
    <div class="row">
        <?$i=0;
        foreach ($themes as $theme=>$info):?>
            <?if(Theme::$theme!==$theme):?>
            <?if ($i%3==0):?><div class="clearfix"></div><?endif?>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="thumbnail ">
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
            <?$i++;
            endif?>
        <?endforeach?>
        <?endif?>
        
        <div class="clearfix"></div>
        <?
        $a_m_themes = count($mobile_themes);
        if(Core::config('appearance.theme_mobile')!='')
            $a_m_themes--;
        
        if ($a_m_themes>0):?>
        <h2><?=__('Available Mobile Themes')?></h2>
        
        
        <?$i=0;
        foreach ($mobile_themes as $theme=>$info):?>
            <?if(Core::config('appearance.theme_mobile')!==$theme):?>
            <?if ($i%3==0):?></ul><div class="row"><ul class="thumbnails"><?endif?>
            <div class="col-md-4">
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
                        <a class="btn btn-default" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a>    
                    </p>
                </div>
            </div>
            </div>
            <?$i++;
            endif?>
        <?endforeach?>
        <?endif?>
        <div class="clearfix"></div>
        
        <?if (count($market)>0):?>
        <h2><?=__('Themes Market')?></h2>
        <p><?=__('Here you can find a selection of our premium themes.')?></p>
        <p class="text-success"><?=__('All themes include support, updates and 1 site license.')?></p> <?=__('Also white labeled and free of ads')?>!
        
        <?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    
<?endif?>
