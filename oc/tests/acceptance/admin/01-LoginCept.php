<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('login as admin');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@eshop.lo');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Welcome admin');

$I->amOnPage('/oc-panel/auth/logout');

$I->see('Login','a');





