<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('crud menu item');

$I->login_admin();

// CREATE  MENU ITEM
$I->amOnPage('/oc-panel/menu/index');
$I->fillField('title','ButtonOne');
$I->fillField('url','https://www.google.com');
$I->fillField('icon','glyphicon glyphicon-euro');
$I->click('button[type="submit"]');

$I->seeElement('.alert.alert-success');
$I->see('Menu created');


// READ MENU ITEM
$I->amOnPage('/');
$I->see('ButtonOne','a');
$I->seeElement('a', ['href' => 'https://www.google.com']);
$I->seeElement('.glyphicon.glyphicon-euro');


// UPDATE ITEM
$I->amOnPage('/oc-panel/menu/index');
$I->click('//a[@class="drag-action ajax-load"]');
$I->see('Edit Menu ButtonOne','h1');
$I->fillField('title','ButtonOneOne');
$I->fillField('url','https://www.yahoo.com');
$I->fillField('icon','glyphicon glyphicon-plus');
$I->click('button[type="submit"]');

$I->amOnPage('/');
$I->see('ButtonOneOne','a');
$I->seeElement('a', ['href' => 'https://www.yahoo.com']);
$I->seeElement('.glyphicon.glyphicon-plus');


// DELETE ITEM
$I->amOnPage('/oc-panel/menu/index');
$I->click('a[class="drag-action index-delete"]');

$I->amOnPage('/');
$I->dontSee('ButtonOneOne','a');
$I->see('Home','a');
$I->see('Listing','a');
$I->see('Search','a');
$I->see('Contact','a');


// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

