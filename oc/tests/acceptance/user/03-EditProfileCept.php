<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('edit my profile');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@gmail.com');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Thanks for using our website.');

// Edit user name and add description
$I->amOnPage('/oc-panel/profile/edit');
$I->fillField('#name','user user');
$I->fillField('#description','user description');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/edit')]/div/div/button[@type='submit']");
$I->seeElement('.alert.alert-success');
$I->see('You have successfuly changed your data');

$I->amOnPage('/oc-panel/profile/edit');
$I->fillField('#name','user');
$I->fillField('#description','');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/edit')]/div/div/button[@type='submit']");
$I->seeElement('.alert.alert-success');
$I->see('You have successfuly changed your data');


// Edit Billing Info
$I->amOnPage('/oc-panel/profile/edit');
$I->selectOption('country','CK');
$I->fillField('city','kitchen');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/billing')]/div/div/button[@type='submit']");
$I->see('Billing information changed');
$I->seeElement('.alert.alert-success');

$I->amOnPage('/oc-panel/profile/edit');
$I->fillField('city','');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/billing')]/div/div/button[@type='submit']");
$I->see('Billing information changed');
$I->seeElement('.alert.alert-success');


// Change password
$I->amOnPage('/oc-panel/profile/edit');
$I->fillField('password1','4321');
$I->fillField('password2','4321');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/changepass')]/div/div/button[@type='submit']");
$I->seeElement('.alert.alert-success');
$I->see('Password is changed');

$I->amOnPage('/oc-panel/profile/edit');
$I->fillField('password1','1234');
$I->fillField('password2','1234');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/changepass')]/div/div/button[@type='submit']");
$I->seeElement('.alert.alert-success');
$I->see('Password is changed');


// Change profile pic
$I->amOnPage('/oc-panel/profile/edit');
$I->attachFile('profile_image','photo.jpg');
$I->click("//form[contains(@action,'http://eshop.lo/oc-panel/profile/image')]/div/div/button[@type='submit']");
$I->see('photo.jpg Image is uploaded.');
$I->seeElement('.alert.alert-success');

// Delete profile pic
$I->amOnPage('/oc-panel/profile/edit');
$I->click('//button[@class="btn btn-danger index-delete index-delete-inline"]');
$I->seeElement('.alert.alert-success');
$I->see('Photo deleted.');


// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

