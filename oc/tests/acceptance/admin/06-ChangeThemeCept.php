<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('change theme and theme options');

$I->login_admin();

// Switch to Olson
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Change theme options, disable breadcrumb
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/search.html');
$I->dontSeeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/search.html');
$I->seeElement('.breadcrumb');


// Switch to kamaleon
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Change theme options, disable breadcrumb
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->fillField('#short_description','Description about the site');
$I->click('submit');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/search.html');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/');
$I->seeElement('.lead');
$I->see('Description about the site');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->fillField('#short_description','');
$I->click('submit');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/search.html');
$I->seeElement('.breadcrumb');
$I->amOnPage('/');
$I->dontSeeElement('.lead');
$I->dontSee('Description about the site');


// Switch to CZsale
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Change theme options, disable breadcrumb
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->fillField('#short_description','Description about the site');
$I->click('submit');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/search.html');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/');
$I->seeElement('.lead');
$I->see('Description about the site');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->fillField('#short_description','');
$I->click('submit');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/search.html');
$I->seeElement('.breadcrumb');
$I->amOnPage('/');
$I->dontSeeElement('.lead');
$I->dontSee('Description about the site');


// Switch to Default
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');



// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');






