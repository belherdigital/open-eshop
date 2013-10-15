<div class="control-group <?php if ($field['error']) echo 'error'; ?>">
	<?php echo View::factory('formmanager/html/label', array('field' => $field))->render(); ?>
	<div class="controls">
		<?php if (isset($field['prefix'])) echo $field['prefix']; ?>
