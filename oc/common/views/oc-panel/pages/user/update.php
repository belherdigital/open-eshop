<?php defined('SYSPATH') or die('No direct script access.');?>
<h1 class="page-header page-title" id="crud-<?=$name?>"><?=__('Update')?> <?=Text::ucfirst(__($name))?></h1>
<hr>
  <p>
    <?$controllers = Model_Access::list_controllers()?>
    <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'index'))?>?email=<?=$form->object->email?>">
      <?=__('Orders')?>
    </a>
    <?if (array_key_exists('ticket', $controllers)) :?>
      - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index','id'=>'admin'))?>?search=<?=$form->object->email?>">
          <?=__('Tickets')?></a>
      </a>
    <?endif?>
    <?if (array_key_exists('ad', $controllers)) :?>
      - <a target="_blank" href="<?=Route::url('profile',array('seoname'=>$form->object->seoname))?>">
          <?=__('Ads')?>
      </a>
    <?endif?>
    <?if (core::config('advertisement.reviews')==1 OR core::config('product.reviews')==1):?>
      - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'review','action'=>'index'))?>?email=<?=$form->object->email?>">
          <?=__('Reviews')?>
      </a>
    <?endif?>
  </p>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
        <?if (Auth::instance()->get_user()->is_admin()):?>
          <div class="panel panel-default">
              <div class="panel-heading" id="page-edit-profile">
                  <h3 class="panel-title"><?=__('Change password')?></h3>
              </div>
              <div class="panel-body">
                  <div class="row">
                      <div class="col-md-8">
                          <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'user','action'=>'changepass','id'=>$form->object->id_user))?>">         
                              <?=Form::errors()?>  
                                    
                              <div class="form-group">
                                  <label class="col-xs-4 control-label"><?=__('New password')?></label>
                                  <div class="col-sm-8">
                                  <input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
                                  </div>
                              </div>
                                
                              <div class="form-group">
                                  <label class="col-xs-4 control-label"><?=__('Repeat password')?></label>
                                  <div class="col-sm-8">
                                  <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
                                      <p class="help-block">
                                            <?=__('Type your password twice to change')?>
                                      </p>
                                  </div>
                              </div>
                                    
                              <div class="form-group">
                                  <div class="col-md-offset-4 col-md-8">
                                      <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                                  </div>
                              </div>
                                    
                          </form>
                      </div>
                  </div>
              </div>
          </div>
        <?endif?>
    </div>
</div>
