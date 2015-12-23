<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('enable/disable captcha/recaptcha');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@eshop.lo');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Welcome admin');

// Enable captcha
$I->amOnPage('/oc-panel/Config/update/captcha');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// See captcha on contact page
$I->amOnPage('/contact.html');
$I->see('Captcha*:');
$I->seeElement('img', ['id' => 'captcha_img_contact']);
$I->seeElement('input', ['id' => 'captcha']);

// Disable captcha
$I->amOnPage('/oc-panel/Config/update/captcha');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Don't see captcha on contact page
$I->amOnPage('/contact.html');
$I->dontSee('Captcha*:');
$I->dontSeeElement('img', ['id' => 'captcha_img_contact']);
$I->dontSeeElement('input', ['id' => 'captcha']);


// Enable captcha
$I->amOnPage('/oc-panel/Config/update/captcha');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Enable reCaptcha
$I->amOnPage('/oc-panel/Config/update/recaptcha_active');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Fill site key
$I->amOnPage('/oc-panel/Config/update/recaptcha_secretkey');
$I->fillField('#formorm_config_value','6LdvORMTAAAAAFn7DmHjrD11JCyU5xgMHOztIl8x');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Fill secret key
$I->amOnPage('/oc-panel/Config/update/recaptcha_sitekey');
$I->fillField('#formorm_config_value','6LdvORMTAAAAAJQreNDSVSrWZyX_xTBSQzqMOIwG');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// See reCaptcha
$I->amOnPage('/contact.html');
$I->seeElement('div', ['id' => 'recaptcha1']);


// Disable recaptcha
$I->amOnPage('/oc-panel/Config/update/captcha');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/recaptcha_active');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/recaptcha_secretkey');
$I->fillField('#formorm_config_value','');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/recaptcha_sitekey');
$I->fillField('#formorm_config_value','');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/contact.html');
$I->dontSeeElement('div', ['id' => 'recaptcha1']);


// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

