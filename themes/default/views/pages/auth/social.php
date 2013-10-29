<?if (Theme::get('premium')==1 AND count($providers = Social::get_providers())>0):?>
<fieldset>
    <legend><?=__('Social Login')?></legend>
    <?foreach ($providers as $key => $value):?>
        <?if($value['enabled']):?>
            <a  class=" oc_social icon <?=$key?>" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>"><?=$key?></a>
        <?endif?>
    <?endforeach?>
</fieldset>
<?endif?>