<?php defined('SYSPATH') or die('No direct script access.');?>
<div id="alternative_pay_modal" class="modal hide fade" data-backdrop="static">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h1><?=$content->title?></h1>
    </div>
    <div class="modal-body">
        <p><?=Text::bb2html($content->description,TRUE,FALSE)?></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=__('Close')?></button>
    </div>
</div>
<button data-toggle="modal" data-target="#alternative_pay_modal" class="btn btn-success pay-btn"><?=$content->title?></button>