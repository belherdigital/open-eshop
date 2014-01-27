<div class="page-header">
    <h1><?=__('Translations')?> <?=$edit_language?></h1>
    <p><?=__('Here you can modify any text you find in your web.')?><a href="http://open-classifieds.com/2013/08/16/how-to-change-texts/" target="_blank"><?=__('Read more')?></a></p>
</div>

<form enctype="multipart/form-data" class="form form-horizontal" accept-charset="utf-8" method="post" action="<?=Request::current()->url()?>">

    <table class="table table-bordered">
    <tr>
        <th>#</th>
        <th><?=__('Original Translation')?></th>
        <th><button class="btn" id="button-copy-all" 
                data-text="<?=__('Copy all?, Be aware this will replace all your texts.')?>" >
                <i class="glyphicon glyphicon-arrow-right"></i></button>
            <?if (strlen(Core::config('general.translate'))>0):?>
                <button id="button-translate-all" class="btn" data-apikey="<?=Core::config('general.translate')?>"
                    data-text="<?=__('Translate all?, Be aware this will replace all your texts.')?>"
                    data-langsource="en" data-langtarget="<?=substr($edit_language,0,2)?>" ><i class="glyphicon glyphicon-globe"></i>
                </button>
            <?endif?>
        </th>
        <th><?=__('Translation')?> <?=$edit_language?></th>
        <th></th>
    </tr>
    <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i> <?=__('Save')?></button>

    <?$cont = 0; $chars=0;?>
    <?foreach($strings_en as $key => $value):?>
    <?$chars+=strlen($key)?>
        <? $value = (isset($strings_default[$key])) ? $strings_default[$key] : ''?>
        <tr id="tr_<?=$cont?>" class="<?=($value)? 'success': 'error'?>">
            <td width="5%"><?=$cont?></td>
            <td>
                <textarea id="orig_<?=$cont?>" disabled style="width: 100%"><?=$key?></textarea>
            </td>
            <td width="5%">
                <button class="btn button-copy" data-orig="orig_<?=$cont?>" data-dest="dest_<?=$cont?>" data-tr="tr_<?=$cont?>" ><i class="glyphicon glyphicon-arrow-right"></i></button>
                <br>
                <?if (strlen(Core::config('general.translate'))>0):?>
                    <button class="btn button-translate" data-orig="orig_<?=$cont?>" data-dest="dest_<?=$cont?>" data-tr="tr_<?=$cont?>" ><i class="glyphicon glyphicon-globe"></i></button>
                <?else:?>
                    <a target="_blank" class="btn" 
                    href="http://translate.google.com/#en/<?=substr($edit_language,0,2)?>/<?=urlencode($key)?>">
                    <i class="glyphicon glyphicon-globe"></i></a>
                <?endif?>
            </td>
            <td>  
                <textarea id="dest_<?=$cont?>" style="width: 100%" name="translations[<?=$cont?>]"><?=$value?></textarea>
            </td>
            <td width="5%">
                <button type="submit" class="btn btn-primary" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i></button>
            </td>
        </tr>
        <?$cont++; //if($cont>10) break;?>
    <?endforeach;?>

    </table>
    <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i> <?=__('Save')?></button>

    <?=$chars?>

    <div id="translate-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

</form>