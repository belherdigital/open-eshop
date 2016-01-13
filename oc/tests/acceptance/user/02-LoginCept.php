<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('login as user');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@gmail.com');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Thanks for using our website.');

// Logout
$I->amOnPage('/oc-panel/auth/logout');

$I->see('Login','a');






