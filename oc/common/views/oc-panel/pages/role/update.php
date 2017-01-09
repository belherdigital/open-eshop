<?php defined('SYSPATH') or die('No direct script access.');?>
<h1 class="page-header page-title"><?=__('Update')?> <?=Text::ucfirst($role->name)?></h1>
<hr>
<form action="<?=Route::url('oc-panel',array('controller'=>'role','action'=>'update','id'=>$role->id_role))?>" method="post" accept-charset="utf-8" class="form form-horizontal" >  

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <input type="hidden" name="id_role" value="<?=$role->id_role?>" />
                    <div class="form-group ">
                        <label for="name" class="col-md-3 control-label"><?=__('Name')?></label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input type="text" id="name" name="name" value="<?=$role->name?>" maxlength="45" />
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label for="description" class="col-md-3 control-label"><?=__('Description')?></label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input type="text" name="description" maxlength="245" value="<?=$role->description?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-offset-3 col-md-5 col-sm-5 col-xs-12">
                            <button type="submit" name="submit" class="btn btn-primary"><?=__('Update')?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?$i=0; foreach ($controllers as $controller=>$actions):?>
            <?if ($i%3==0):?></div><div class="row"><?endif?>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>
                                <div class="checkbox check-success">
                                    <?=FORM::checkbox($controller.'|*', 'on', (bool) in_array($controller.'.*',$access_in_use), ['id' => $controller.'|*'])?>
                                    <label for="<?=$controller.'|*'?>"><?=$controller?>.*</label>
                                </div>
                            </h4>
                            <p>
                                <?foreach ($actions as $action):?>
                                <div class="checkbox check-success">
                                    <?=FORM::checkbox($controller.'|'.$action, 'on', (bool) in_array($controller.'.'.$action,$access_in_use), ['id' => $controller.'|'.$action])?>
                                    <label for="<?=$controller.'|'.$action?>"><?=$action?></label>
                                </div>
                                <?endforeach?>
                            </p>
                        </div>
                    </div>
                </div>
            <?$i++;
        endforeach?>
    </div><!--/row-->

    <div class="form-actions">
        <button type="submit" name="submit" class="btn btn-primary"><?=__('Update')?></button>
    </div>

</form>