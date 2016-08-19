<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('use Professional Support');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@gmail.com');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Thanks for using our website.');

// Click buy product to create the order
$I->amOnPage('/themes/default-theme.html');
$I->click('a[href="http://eshop.lo/product/buy/1"]');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

// Login as admin to mark order as paid
$I->login_admin();

$I->amOnPage('/oc-panel/Order/update/1');
$I->fillField('formorm[pay_date]','2015-11-30');
$I->fillField('formorm[support_date]','2016-11-30');
$I->selectOption('formorm[status]','1');
$I->click('formorm[submit]');
$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

// Use Support
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@gmail.com');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Thanks for using our website.');

$I->amOnPage('/oc-panel/support/new');
$I->see('New Ticket','h1');
$I->selectOption('order','1');
$I->fillField('title','Need help!');
$I->fillField('#description','I need help guys!! I need some characters to fill this field in order to proceed!');
$I->click('button[type="submit"]');

$I->seeElement('.alert.alert-success');
$I->see('Ticket created.');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

// Answer the ticket
$I->login_admin();

$I->amOnPage('/oc-panel/support/index/admin');
$I->see('Need help');
$I->see('New','span');
$I->click('a[href="http://eshop.lo/oc-panel/support/ticket/1"]');
$I->see('Need help','h1');
$I->see('Default Theme');
$I->see('250USD 30-11-15','a');
$I->see('I need help guys!! I need some characters to fill this field in order to proceed!','p');
$I->fillField('#description','Hello! I can see you need help! We are here to help you!');
$I->click('button[type="submit"]');

$I->seeElement('.alert.alert-success');
$I->see('Reply created.');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

// See the reply and close ticket
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Thanks for using our website.');

$I->amOnPage('/oc-panel/support/index');
$I->click('a[href="http://eshop.lo/oc-panel/support/ticket/1"]');
$I->see('Hello! I can see you need help! We are here to help you!');
$I->click('a[href="http://eshop.lo/oc-panel/support/close/1"]');

$I->seeElement('.alert.alert-success');
$I->see('Ticket closed.');
$I->see('Closed','span');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

// Login as admin to see closed ticket
$I->login_admin();

$I->amOnPage('/oc-panel/support/index/admin');
$I->see('Closed','span');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');



