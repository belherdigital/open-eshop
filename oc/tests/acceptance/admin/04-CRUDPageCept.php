<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('crud a page');

$I->login_admin();

// CREATE PAGE
$I->amOnPage('/oc-panel/content/create?type=page');

$I->see('Create Page','h1');
$I->fillField('#title','About');
$I->fillField('#description','All about us!!');
$I->fillField('#seotitle','about');
$I->checkOption('status');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Success');


// READ PAGE
$I->amOnPage('/about.html');
$I->see('About','h1');
$I->see('All about us!!');


// UPDATE PAGE
$I->amOnPage('/oc-panel/content/edit/13');
$I->see('Edit Page','h1');
$I->fillField('#title','About Us');
$I->fillField('#description','All you need to know about us!!');
$I->fillField('#seotitle','about-us');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('page is edited');

// read updated page
$I->amOnPage('/about-us.html');
$I->see('About Us','h1');
$I->see('All you need to know about us!!');

// DELETE PAGE
$I->amOnPage('/oc-panel/content/page');
$I->see('About us');
$I->click('.btn.btn-danger.index-delete');

$I->amOnPage('/oc-panel/content/page');
$I->dontSee('About us');
$I->amOnPage('/about-us.html');
$I->see('Page not found','h2');

// Logout
$I->amOnPage('/oc-panel/auth/logout');

$I->see('Login','a');



























