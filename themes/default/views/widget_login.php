<?php defined('SYSPATH') or die('No direct script access.');?>
<?if (Auth::instance()->logged_in()):?>
<a class="btn btn-success"
	href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
	<i class="icon-user icon-white"></i> 
</a>
<a class="btn dropdown-toggle btn-success" data-toggle="dropdown"
	href="#"> <span class="caret"></span>
</a>
<ul class="dropdown-menu">
	
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
        <i class="icon-cog icon-white"></i> <?=__('Panel')?></a></li>

    <li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>"><i
           class="icon-shopping-cart icon-white"></i> <?=__('My Purchases')?></a></li>
	
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>"><i
		   class="icon-lock icon-white"></i> <?=__('Edit profile')?></a></li>

    

	<li class="divider"></li>
	<li><a
		href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
			<i class="icon-off icon-white"></i> <?=__('Logout')?>
	</a>
	</li>
    <li>
        <a
        href="<?=Route::url('default')?>">
            <i class="icon-home icon-white"></i> <?=__('Visit Site')?></a>
    </li>
</ul>
<?else:?>
<a class="btn" data-toggle="modal" title="<?=__('Login')?>"
	href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
	<i class="icon-user icon-white"></i> <?=__('Login')?>
</a>
<?endif?>