<?php defined('SYSPATH') or die('No direct script access.');?>
<a class="btn btn-success"
	href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
	<i class="icon-user icon-white"></i> 
</a>
<a class="btn dropdown-toggle btn-success" data-toggle="dropdown"
	href="#"> <span class="caret"></span>
</a>
<ul class="dropdown-menu">
	
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
        <i class="icon-cog"></i> <?=__('Panel')?></a></li>

    <li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'ads'))?>"><i
           class="icon-edit"></i> <?=__('My Advertisements')?></a></li>
	
	<li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>"><i
		   class="icon-lock"></i> <?=__('Edit profile')?></a></li>

    <li><a href="<?=Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname))?>">
         <i class="icon-eye-open"></i> <?=__('Public profile')?></a></li>

	<li class="divider"></li>
	<li><a
		href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
			<i class="icon-off"></i> <?=__('Logout')?>
	</a></li>
    <li>
        <a
        href="<?=Route::url('default')?>">
            <i class="icon-home"></i> <?=__('Visit Site')?></a>
	</li>
</ul>