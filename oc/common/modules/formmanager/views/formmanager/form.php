<?php echo Form::open($form->action, $form->attributes); ?>
	
	<?php if ($form->fieldsets): ?>
		<?php foreach ($form->fields as $field): if ($field['display_as'] == 'hidden'): ?>
			<?php echo View::factory('formmanager/html/hidden', array('field' => $field))->render(); ?>
		<?php endif; endforeach; ?>
		<?php echo View::factory('formmanager/html/fieldsets', array('fieldsets' => $form->fieldsets))->render(); ?>
	<?php else: ?>
		<?php echo View::factory('formmanager/html/fields', array('fields' => $form->fields))->render(); ?>
	<?php endif; ?>

    <?php echo View::factory('formmanager/html/buttons', array('buttons' => $form->buttons))->render(); ?>

<?php echo Form::close(); ?>