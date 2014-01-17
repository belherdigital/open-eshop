<div class="form-group <?php if ($field['error']) echo 'error'; ?>">
	<?php echo View::factory('formmanager/html/label', array('field' => $field))->render(); ?>
	<div class="col-md-5 col-sm-5 col-xs-12">
		<?php if (isset($field['prefix'])) echo $field['prefix']; ?>
