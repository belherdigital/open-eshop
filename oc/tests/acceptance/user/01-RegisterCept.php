<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('register');

$I->amOnPage('/oc-panel/auth/register');
$I->see('Register','h1');

$I->fillField("//form[contains(@action,'http://eshop.lo/oc-panel/auth/register')]/div/div/input[@name='name']","user");
$I->fillField("//form[contains(@action,'http://eshop.lo/oc-panel/auth/register')]/div/div/input[@name='email']","user@gmail.com");
$I->fillField("//form[contains(@action,'http://eshop.lo/oc-panel/auth/register')]/div/div/input[@name='password1']","1234");
$I->fillField("//form[contains(@action,'http://eshop.lo/oc-panel/auth/register')]/div/div/input[@name='password2']","1234");
$I->click(".register button[type=submit]");

$I->amOnPage('/oc-panel/');
$I->see('Welcome');
$I->see('user');

// Logout
$I->amOnPage('/oc-panel/auth/logout');



