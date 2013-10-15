<?php foreach ($field['options'] as $option_key => $option): ?>
		<label class="checkbox">
			<input 
				type="checkbox" 
				name="<?php echo $field['field_name']; ?>[]" 
				value="<?php echo $option_key; ?>" 
				<?php if (in_array($option_key, $field['value'])): ?>checked="checked"<?php endif; ?>
			/> 
			<?php echo $option; ?>
		</label>
<?php endforeach; ?>

