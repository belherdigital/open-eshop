<?php defined('SYSPATH') or die('No direct script access.');?>
<?if (Auth::instance()->logged_in()):?>
<a class="btn btn-success"
	href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
	<i class="glyphicon glyphicon-user "></i> 
</a>
<a class="btn dropdown-toggle btn-success" data-toggle="dropdown"
	href="#"> <span class="caret"></span>
</a>
<ul class="dropdown-menu">
	
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
        <i class="glyphicon glyphicon-cog"></i> <?=__('Panel')?></a></li>


    <li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>"><i
           class="glyphicon glyphicon-shopping-cart"></i> <?=__('My Purchases')?></a></li>
	
    <li><a href="<?=Route::url('oc-panel',array('controller'=>'support'))?>"><i
               class="glyphicon glyphicon-comment"></i> <?=__('Support')?></a></li>
               
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>"><i
		   class="glyphicon glyphicon-lock"></i> <?=__('Edit profile')?></a></li>

	<li class="divider"></li>
	<li><a
		href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
			<i class="glyphicon glyphicon-off"></i> <?=__('Logout')?>
	</a>
	</li>
    <li>
        <a
        href="<?=Route::url('default')?>">
            <i class="glyphicon glyphicon-home"></i> <?=__('Visit Site')?></a>
    </li>
</ul>
<?else:?>
<a class="btn btn-primary-white" data-toggle="modal" 
	href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
	<i class="glyphicon glyphicon-user"></i> <?=__('Login')?>
</a>
<?endif?>