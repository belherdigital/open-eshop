<?=Theme::styles($styles,'default')?>   
<?=Theme::scripts($scripts,'header','default')?>

<?=Breadcrumbs::render('oc-panel/breadcrumbs')?>      
<?=Alert::show()?>

<?=$content?>

<?=Theme::scripts($scripts,'footer','default')?>
	    	
<?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>