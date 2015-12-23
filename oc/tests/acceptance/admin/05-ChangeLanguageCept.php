<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('change language');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@eshop.lo');
$I->fillField('password','1234');

$I->click('auth_redirect');

$I->amOnPage('/oc-panel');
$I->see('Welcome admin');


$I->amOnPage('/oc-panel/translations/index');
$I->see('Translations','h1');
$I->see('Translations files available in the system');

$I->click('a[href="http://eshop.lo/oc-panel/translations/index/es_ES"]');

$I->see('Éxito');
$I->seeElement('.alert.alert-success');

$I->see('Traducciones','h1');
$I->see('Archivos en el sistema de traducciones');
$I->see('Idioma');

$I->amOnPage('/');
$I->see('Más reciente','h2');
$I->see('Categorías','h2');
$I->see('Inicio','a');
$I->see('Listado','a');
$I->see('Buscar','a');
$I->see('Contacto','a');

$I->amOnPage('/oc-panel/translations/index');
$I->click('a[href="http://eshop.lo/oc-panel/translations/index/en_US"]');

$I->seeElement('.alert.alert-success');
$I->see('Translations regenarated');
$I->see('Translations','h1');




// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');



























