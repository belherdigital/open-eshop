<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?=Route::url('default')?>"><?=core::config('general.site_name')?></a>
			
			<?
            $cats = Model_Category::get_category_count();
            $loc_seoname = NULL;
            if (Controller::$location!==NULL)
            {
                if (Controller::$location->loaded())
                    $loc_seoname = Controller::$location->seoname;
            }
            ?>
			
			<div class="nav-collapse main_nav">
				<ul class="nav">
					<?nav_link(__('Listing'),'ad', 'icon-list' ,'listing', 'list')?>
					<li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=__('Categories')?> <b class="caret"></b></a>
		              <ul class="dropdown-menu">

		              	<?foreach($cats as $c ):?>
		              		<?if($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
								<li class="nav-header dropdown-submenu">
                                    <p><a tabindex="-1" title="<?=$c['seoname']?>" href="<?=Route::url('list', array('category'=>$c['seoname'],'location'=>$loc_seoname))?>">
                                        <?=$c['name']?></a>
                                    </p>
									<ul class="dropdown-menu">							
								 	<?foreach($cats as $chi):?>
	                            	<?if($chi['id_category_parent'] == $c['id_category']):?>
	                           			<li class="span4">
                                            <a title="<?=$chi['name']?>" href="<?=Route::url('list', array('category'=>$chi['seoname'],'location'=>$loc_seoname))?>">
                                                <span class="header_cat_list"><?=$chi['name']?></span> 
                                                <span class="count_ads"><span class="badge badge-success"><?=$chi['count']?></span></span>
                                            </a>
                                        </li>
	                           		<?endif?>
	                         		<?endforeach?>
									</ul>
								</li>
							<?endif?>
						<?endforeach?>
		              </ul>
		            </li>
                    <?nav_link('','ad', 'icon-search ', 'advanced_search', 'search')?>
                    <?if (core::config('advertisement.map')==1):?>
                        <?nav_link('','map', 'icon-globe ', 'index', 'map')?>
                    <?endif?>
                    <?nav_link('','contact', 'icon-envelope ', 'index', 'contact')?>
                    <?nav_link('','rss', 'icon-signal ', 'index', 'rss')?>
		        </ul>
		        <?= FORM::open(Route::url('search'), array('class'=>'navbar-search pull-left', 'method'=>'GET', 'action'=>''))?>
		            <input type="text" name="search" class="search-query span2" placeholder="<?=__('Search')?>">
		        <?= FORM::close()?>

				<div class="btn-group pull-right">
					<?=View::factory('widget_login')?>
				
					<a class="btn btn-danger" href="<?=Route::url('post_new')?>">
						<i class="icon-pencil icon-white"></i>
						<?=__('Publish new ')?>
					</a>				

				</div>
				
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>

<?if (!Auth::instance()->logged_in()):?>
	<div id="login-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Login')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/login-form')?>
    		</div>
    </div>
    
    <div id="forgot-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Forgot password')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/forgot-form')?>
    		</div>
    </div>
    
     <div id="register-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Register')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/register-form')?>
    		</div>
    </div>
<?endif?>