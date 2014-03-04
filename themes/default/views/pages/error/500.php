<?php defined('SYSPATH') or die('No direct script access.');?>
 

<div>

    <?if (Auth::instance()->get_user()):?>
    <?if (Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN):?>
        <p>Since you are loged in as admin only you can see this message:</p>
        <code><?=$message?></code>
        <p>It's been loged in Panel->Tools->Logs for more information regarding this error.</p>
    <?endif?>
    <?endif?>

    <h2>Wow this seems to be an error...</h2>
    <p>Something went wrong while we are processing your request. You can try the following:</p>

    <ul>
        <li>Reload / refresh the page.</li>
        <li>Go back to the previous page.</li>
    </ul>

    <p>This incident is logged and we are already notified about this problem.
    We will trace the cause of this problem.</p>

    <p>For the mean time, you may want to go to the main page.</p>

    <p><a href="<?php echo URL::site('/', TRUE) ?>">If you wanted to go to the main page, click here.</a></p>

</div>