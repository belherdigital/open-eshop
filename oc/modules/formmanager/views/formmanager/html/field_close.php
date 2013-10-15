		<?php if ($field['error'] && $field['error_text']): ?><span class="help-inline"><?php echo $field['error_text']; ?></span><?php endif; ?>
		<?php if ($field['help']): ?><p class="help-block"><?php echo $field['help']; ?></p><?php endif; ?>
		<?php if (isset($field['suffix'])) echo $field['suffix']; ?>
	</div>
</div>

