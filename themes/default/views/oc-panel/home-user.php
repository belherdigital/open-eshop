<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="hero-unit">
    <h1>
    <?=__('Welcome')?>
    <?=Auth::instance()->get_user()->name?>
        &nbsp; <small><?=Auth::instance()->get_user()->email?> </small>
    </h1>

    <p><?=__('Thanks for using our website.')?> </a>
</div>