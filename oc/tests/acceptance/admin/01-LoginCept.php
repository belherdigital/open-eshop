<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('login as admin');

$I->login_admin();

$I->amOnPage('/oc-panel/auth/logout');

$I->see('Login','a');





