<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('crud a widget');

$I->login_admin();

// CREATE WIDGET
$I->amOnPage('/oc-panel/widget/index');
$I->click('//button[@data-target="#modal_Widget_Search"]');
$I->selectOption('placeholder','sidebar');
$I->fillField('#text_title','Search Widget');
$I->selectOption('advanced','1');
$I->click('Save changes');

$I->seeElement('.alert.alert-success');
$I->see('Widget created in sidebar');

// READ
// On default theme
$I->amOnPage('/');
$I->seeElement('.panel.panel-sidebar.Widget_Search');
$I->see('Search Widget','h3');
$I->see('Product Title','label');
$I->see('Categories','label');
$I->see('Price from','label');
$I->see('Price to','label');
$I->seeElement('input', ['name' => 'title']);
$I->seeElement('select', ['name' => 'category']);
$I->seeElement('input', ['name' => 'price-min']);
$I->seeElement('input', ['name' => 'price-max']);
$I->seeElement('button', ['name' => 'submit']);

// Switch to Olson
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->seeElement('.cool-block.mb-20');
$I->seeElement('.cool-block-bor');
$I->see('Search Widget','h3');
$I->see('Product Title','label');
$I->see('Categories','label');
$I->see('Price from','label');
$I->see('Price to','label');
$I->seeElement('input', ['name' => 'title']);
$I->seeElement('select', ['name' => 'category']);
$I->seeElement('input', ['name' => 'price-min']);
$I->seeElement('input', ['name' => 'price-max']);
$I->seeElement('button', ['name' => 'submit']);


// Switch to kamaleon
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->seeElement('.category_box_title.custom_box');
$I->seeElement('.well.custom_box_content');
$I->see('Search Widget','h3');
$I->see('Product Title','label');
$I->see('Categories','label');
$I->see('Price from','label');
$I->see('Price to','label');
$I->seeElement('input', ['name' => 'title']);
$I->seeElement('select', ['name' => 'category']);
$I->seeElement('input', ['name' => 'price-min']);
$I->seeElement('input', ['name' => 'price-max']);
$I->seeElement('button', ['name' => 'submit']);



// Switch to CZsale
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Search Widget','h3');
$I->see('Product Title','label');
$I->see('Categories','label');
$I->see('Price from','label');
$I->see('Price to','label');
$I->seeElement('input', ['name' => 'title']);
$I->seeElement('select', ['name' => 'category']);
$I->seeElement('input', ['name' => 'price-min']);
$I->seeElement('input', ['name' => 'price-max']);
$I->seeElement('button', ['name' => 'submit']);

// Switch to Default
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');


// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

