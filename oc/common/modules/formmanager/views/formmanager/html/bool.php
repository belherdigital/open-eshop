<div class="checkbox check-success">
	<?=Form::checkbox($field['field_name'], '1', (bool)$field['value'], ['id' => $field['field_name'],])?>
	<label for="<?=$field['field_name']?>"></label>
</div>