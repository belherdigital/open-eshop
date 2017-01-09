<?if (Theme::get('premium')==1):?>
    <?if (count($providers = Social::enabled_providers()) > 0) :?>
        <ul class="list-inline social-providers">
            <?foreach ($providers as $key => $provider) :?>     
                <li>
                    <a class="zocial <?=strtolower($key) == 'live' ? 'windows' : strtolower($key)?> social-btn" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>">
                        <?=$key?>
                    </a>
                </li>
            <?endforeach?>
        </ul>
    <?endif?>
<?endif?>