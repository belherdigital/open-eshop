<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('crud a category');

$I->login_admin();

// CREATE
$I->amOnPage('/oc-panel/category/create');

$I->fillField('#formorm_name','Themes');
$I->fillField('#formorm_seoname','themes');
$I->fillField('#formorm_description','Premium themes and templates for Open Classifieds.');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Category created');


// READ
$I->amOnPage('/');
$I->see('themes','a');

$I->amOnPage('/themes');
$I->see('Themes','h1');
$I->see('Premium themes and templates for Open Classifieds.','p');


// UPDATE
$I->amOnPage('/oc-panel/Category');
$I->click('.btn.btn-xs.btn-primary.pull-right');
$I->see('Update Category','h1');

$I->fillField('#formorm_name','Themes-updated');
$I->fillField('#formorm_seoname','themes-updated');
$I->fillField('#formorm_description','Premium themes and templates for Open Classifieds. Themes, themes and themes!');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated');

$I->amOnPage('/');
$I->see('themes-updated','a');

$I->amOnPage('/themes-updated');
$I->see('Themes-updated','h1');
$I->see('Premium themes and templates for Open Classifieds. Themes, themes and themes!','p');


// DELETE
$I->amOnPage('/oc-panel/Category');
$I->click('.btn.btn-xs.btn-danger.pull-right.index-delete.index-delete-inline');

$I->amOnPage('/oc-panel/Category');
$I->dontSeeElement('.btn.btn-xs.btn-danger.pull-right.index-delete.index-delete-inline');
$I->dontSeeElement('.btn.btn-xs.btn-primary.pull-right');






































$I->amOnPage('/oc-panel/auth/logout');

$I->see('Login','a');





