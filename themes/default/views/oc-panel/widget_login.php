<?php defined('SYSPATH') or die('No direct script access.');?>
<a class="btn btn-success navbar-btn"
	href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
	<i class="glyphglyphicon glyphicon-user glyphicon"></i> 
</a>
<a class="btn dropdown-toggle btn-success navbar-btn"  data-toggle="dropdown"
	href="#"> <span class="caret"></span>
</a>

<ul class="dropdown-menu">
	
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
        <i class="glyphicon glyphglyphicon glyphicon-cog"></i> <?=__('Panel')?></a></li>

    <li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>"><i
           class="glyphicon glyphglyphicon glyphicon-shopping-cart"></i> <?=__('My Purchases')?></a></li>

    <li><a href="<?=Route::url('oc-panel',array('controller'=>'support'))?>"><i
               class="glyphicon glyphicon-comment"></i> <?=__('Support')?></a></li>

	<li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>"><i
		   class="glyphicon glyphglyphicon glyphicon-lock"></i> <?=__('Edit profile')?></a></li>

	<li class="divider"></li>
	<li><a
		href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
			<i class="glyphicon glyphglyphicon glyphicon-off"></i> <?=__('Logout')?>
	</a></li>
    <li>
        <a
        href="<?=Route::url('default')?>">
            <i class="glyphicon glyphglyphicon glyphicon-home"></i> <?=__('Visit Site')?></a>
	</li>
</ul>
