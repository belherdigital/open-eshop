<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('change product configurations');

$I->login_admin();

// Select Featured products in homepage slider
$I->amOnPage('/oc-panel/settings/product');
$I->selectOption('#products_in_home','1');
$I->click('submit');

// Dont see latest
$I->amOnPage('/');
$I->dontSee('Latest','h2');
$I->dontSee('This is the Default theme','p');

// Select Popular Last Month in homepage slider
$I->amOnPage('/oc-panel/settings/product');
$I->selectOption('#products_in_home','2');
$I->click('submit');

// See popular products
$I->amOnPage('/');
$I->see('Most popular','h2');
$I->see('This is the Default theme','p');

// Select Best Rated in homepage slider
$I->amOnPage('/oc-panel/settings/product');
$I->selectOption('#products_in_home','3');
$I->click('submit');

// See best rated products
$I->amOnPage('/');
$I->see('Best rated','h2');
$I->see('This is the Default theme','p');

// Select None in homepage slider
$I->amOnPage('/oc-panel/settings/product');
$I->selectOption('#products_in_home','4');
$I->click('submit');

// No products
$I->amOnPage('/');
$I->dontSee('Best rated','h2');
$I->dontSee('Latest','h2');
$I->dontSee('Most popular','h2');
$I->dontSee('This is the Default theme','p');

// Back to latest products
$I->amOnPage('/oc-panel/settings/product');
$I->selectOption('#products_in_home','0');
$I->click('submit');
$I->amOnPage('/');
$I->see('Latest','h2');
$I->see('This is the Default theme','p');


// Disqus on Product Page
$I->amOnPage('/oc-panel/settings/product');
$I->fillField('#disqus','eshoplo');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Product Configuration updated');

// See disqus on product page
$I->amOnPage('/themes/default-theme.html');
$I->seeElement('div', ['id' => 'disqus_thread']);

// Disable disqus
$I->amOnPage('/oc-panel/settings/product');
$I->fillField('#disqus','');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Product Configuration updated');


// Enable Number of sales
$I->amOnPage('/oc-panel/Config/update/number_of_orders');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// See number of sales
$I->amOnPage('/themes/default-theme.html#details');
$I->seeElement('.glyphicon.glyphicon-shopping-cart');

// Disable number of sales
$I->amOnPage('/oc-panel/Config/update/number_of_orders');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');


// Disable Count visits product
$I->amOnPage('/oc-panel/Config/update/count_visits');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Dont see counter
$I->amOnPage('/themes/default-theme.html#details');
$I->dontSee('Hits :');

// Enable Count visits product
$I->amOnPage('/oc-panel/Config/update/count_visits');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// See counter
$I->amOnPage('/themes/default-theme.html#details');
$I->see('Hits :');







// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

