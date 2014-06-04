<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="accept_terms_modal" class="modal fade" data-backdrop="static">
	<?$content = Model_Content::get_by_title(core::config('general.alert_terms'))?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick='location.href="http://www.google.com"' aria-hidden="true">&times;</button>
				<h1><?=$content->title?></h1>
			</div>
			<div class="modal-body">
				<p><?=Text::bb2html($content->description,TRUE,FALSE)?></p>
			</div>
			<div class="modal-footer">
				<a name="decline_terms" class="btn btn-default" onclick='location.href="http://www.google.com"' ><?=__('No')?></a>
				<a name="accept_terms" class="btn btn-success" onclick='setCookie("accept_terms",1,10000)' data-dismiss="modal" aria-hidden="true"><?=__('I accept')?></a>
			</div>
		</div>
	</div>
</div>

