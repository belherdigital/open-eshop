<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('crud a product');

$I->login_admin();

// CREATE CATEGORY
$I->amOnPage('/oc-panel/category/create');

$I->fillField('#formorm_name','Themes');
$I->fillField('#formorm_seoname','themes');
$I->fillField('#formorm_description','Premium themes and templates for Open Classifieds.');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Category created');


// READ CATEGORY
$I->amOnPage('/');
$I->see('themes','a');

$I->amOnPage('/themes');
$I->see('Themes','h1');
$I->see('Premium themes and templates for Open Classifieds.','p');



// CREATE PRODUCT 
$I->amOnPage('/oc-panel/Product/create');
$I->selectOption('#radio_themes','3');
$I->fillField('#price','200');
$I->selectOption('#currency','USD');
$I->fillField('#title','Default');
$I->fillField('#description','Default theme');
$I->attachFile('image0','photo.jpg');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Product is created.');

$I->amOnPage('/oc-panel/Product/update/1');
$I->checkOption('status');
$I->click('submit');


// READ PRODUCT
$I->amOnPage('/');
$I->see('Latest','h2');
$I->seeElement('.active.item');
$I->seeElement('.min-h');
$I->seeElement('.caption');
$I->see('$200','a');

$I->amOnPage('/all');
$I->seeElement('.item.grid-group-item.col-xs-4.col-lg-4');
$I->seeElement('.caption');
$I->seeElement('.fa.fa-shopping-cart');
$I->see('Buy Now $200.00','a');

$I->amOnPage('/themes');
$I->seeElement('.item.grid-group-item.col-xs-4.col-lg-4');
$I->seeElement('.caption');
$I->seeElement('.fa.fa-shopping-cart');
$I->see('Buy Now $200.00','a');

$I->amOnPage('/themes/default.html');
$I->seeElement('.main-image');
$I->seeElement('.carousel-indicators');
$I->seeElement('.single-h1');
$I->see('Default','h1');
$I->seeElement('.glyphicon.glyphicon-edit');
$I->see('Price : $200.00','h4');
$I->seeElement('.btn.btn-success.btn-large.full-w');
$I->see('Description','a');
$I->see('Details','a');
$I->see('Default theme');

// UPDATE PRODUCT
$I->amOnPage('/oc-panel/Product/update/1');
$I->see('Edit Product Default','h1');
$I->fillField('#price','250');
$I->fillField('#title','Default Theme');
$I->fillField('#description','This is the Default theme');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Product saved');

// read updated product
$I->amOnPage('/');
$I->see('Latest','h2');
$I->seeElement('.active.item');
$I->seeElement('.min-h');
$I->seeElement('.caption');
$I->see('$250','a');

$I->amOnPage('/all');
$I->seeElement('.item.grid-group-item.col-xs-4.col-lg-4');
$I->seeElement('.caption');
$I->seeElement('.fa.fa-shopping-cart');
$I->see('Buy Now $250.00','a');

$I->amOnPage('/themes');
$I->seeElement('.item.grid-group-item.col-xs-4.col-lg-4');
$I->seeElement('.caption');
$I->seeElement('.fa.fa-shopping-cart');
$I->see('Buy Now $250.00','a');

$I->amOnPage('/themes/default-theme.html');
$I->seeElement('.main-image');
$I->seeElement('.carousel-indicators');
$I->seeElement('.single-h1');
$I->see('Default Theme','h1');
$I->seeElement('.glyphicon.glyphicon-edit');
$I->see('Price : $250.00','h4');
$I->seeElement('.btn.btn-success.btn-large.full-w');
$I->see('Description','a');
$I->see('Details','a');
$I->see('This is the Default theme');

// There is no option to delete the product, so there's no need to delete the category.

$I->amOnPage('/oc-panel/auth/logout');

$I->see('Login','a');





