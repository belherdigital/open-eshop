<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="navbar navbar-inverse navbar-fixed-top">

    <div class="navbar-inner">

        <div class="container">

            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="brand" href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><?=__('Panel')?></a>

            <div class="btn-group pull-right visible-desktop">
                <?=View::factory('oc-panel/widget_login')?>
            </div>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <?=Theme::admin_link(__('Market'), 'market','index','oc-panel','icon-gift icon-white')?>
                    <? if(core::config('general.moderation') == 1 OR // moderation on  
                          core::config('general.moderation') == 4 OR // email confiramtion with moderation
                          core::config('general.moderation') == 5):  // payment with moderation?>
                    <?=Theme::admin_link(__('Moderation'),'ad','moderate','oc-panel','icon-ban-circle icon-white')?>
                    <? endif?>
                	<?=Theme::admin_link(__('Stats'),'stats','index','oc-panel','icon-align-left icon-white')?>
                    <?=Theme::admin_link(__('Widgets'),'widget','index','oc-panel','icon-move icon-white')?>
                    <?=Theme::admin_link(__('Cache'),'tools','cache','oc-panel','icon-cog icon-white')?>
                    <? if(Auth::instance()->get_user()->id_role==10):?>
            	    <li  class="dropdown "><a href="#" class="dropdown-toggle"
            		      data-toggle="dropdown"><i class="icon-plus icon-white"></i> <?=__('New')?> <b class="caret"></b></a>
                    	<ul class="dropdown-menu">
                            <?=Theme::admin_link(__('Category'),'category','create')?>
                            <?=Theme::admin_link(__('Location'),'location','create')?>
                            <?=Theme::admin_link(__('Page'),'content','create')?>
                    		<li class="divider"></li>
                    		<li><a href="<?=Route::url('post_new')?>">
                    			<i class="icon-pencil"></i> <?=__('Publish new')?></a>	</li>
                    	</ul>
            	   </li> 
                   <?else:?>
                    <li><a href="<?=Route::url('post_new')?>">
                                <i class="icon-pencil icon-white"></i> <?=__('Publish new')?></a>
                    </li>
                   <?endif?>

                </ul>
                
                <div class="nav pull-right">
                    <ul class="nav">
                        <li>
                            <a href="<?=Route::url('default')?>">
                                    <i class="icon-home icon-white"></i>
                                <?=_('Visit Site')?>
                            </a>
                        </li>
                    </ul>
                </div>

            </div> <!--/.nav-collapse -->

        </div><!--/.container -->

    </div><!--/.navbar-inner -->

</div><!--/.navbar -->