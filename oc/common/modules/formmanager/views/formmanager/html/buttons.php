<div class="form-actions">
<?php
foreach($buttons as $button) {
	echo Form::button($button['name'], $button['text'], $button['attributes']), "\n";
}
?>
</div>