<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=__('Update')?> <?=ucfirst(__($name))?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?=__('Order Licenses')?></h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped">
            <th><?=__('License')?></th>
            <th><?=__('Created')?></th>
            <th><?=__('Domain')?></th>
            <th></th>
            <?foreach ($licenses as $license):?>
                <tr>
                    <td><?=$license->license?></td>
                    <td><?=$license->created?></td>
                    <td><?=($license->status==Model_License::STATUS_NOACTIVE)?__('Inactive'):$license->domain?></td>
                    <td><a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> 'license', 'action'=>'update','id'=>$license->pk()))?>">
                        <i class="glyphicon glyphicon-edit"></i></a></td>
                <tr>
            <?endforeach?>    
        </table>
    </div>
</div>