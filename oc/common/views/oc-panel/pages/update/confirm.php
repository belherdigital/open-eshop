<?php defined('SYSPATH') or die('No direct script access.');?>


<h1 class="page-header page-title"><?=__('Update')?> <?=$latest_version?></h1>
<hr>
    <p>
        <?=__('Your installation version is')?> <span class="label label-info"><?=core::VERSION?></span>
    </p>
    
<div class="alert alert-danger" role="alert">
<?if ($can_update==FALSE):?>
    <h4 class="alert-heading"><?=__('Not possible to auto update')?></h4>
    <p>
        <?=__('You have an old version and automatic update is not possible. Please read the release notes and the manual update instructions.')?>
        <br>
        <a target="_blank"  class="btn btn-default" href="<?=$version['blog']?>"><?=__('Release Notes')?> <?=$latest_version?></a>
    </p>
<?else:?>
    <h2 class="alert-heading"><?=__('Read carefully')?>!</h2>
    <p>
        <ul>
            <li><?=__('Backup all your files and database')?>. <a target="_blank" href="https://docs.yclas.com/backup-classifieds-site/"><?=__('Read more')?></a></li>
            <li><?=__('This process can take few minutes DO NOT interrupt it')?></li>
            <li><?=__('If you have doubts check the release notes for this version')?>. <a target="_blank" href="<?=$version['changelog']?>"><?=__('Release Notes')?> <?=$latest_version?></a></li>
            <li><?=__('You are responsible for any damages or down time at your site')?></li>
        </ul>
    </p>
    <br>
    <a class="btn btn-warning confirm-button"
            title="<?=__('Are you sure you want to update?')?>" 
            data-text="<?=__('This process can take few minutes DO NOT interrupt it')?>"
            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
            data-btnCancelLabel="<?=__('No way!')?>"
            href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'latest'))?>" 
    >
    <span class="glyphicon  glyphicon-refresh"></span> <?=__('Proceed with Update')?>
    </a>
<?endif?>
</div>


<!--/well-->
<div class="modal modal-statc fade" id="processing-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-body">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?=__('Updating, do not close this window.')?></h4>
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>