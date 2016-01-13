<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('use contact us form');

$I->amOnPage('/contact.html');

$I->see('Contact Us','h1');

$I->fillField("//form[contains(@action,'http://eshop.lo/contact.html')]/fieldset/div/div/input[@id='name']","name Name");
$I->fillField("//form[contains(@action,'http://eshop.lo/contact.html')]/fieldset/div/div/input[@id='email']","name@gmail.com");
$I->fillField("//form[contains(@action,'http://eshop.lo/contact.html')]/fieldset/div/div/input[@id='subject']","name@gmail.com");
$I->fillField("//form[contains(@action,'http://eshop.lo/contact.html')]/fieldset/div/div/input[@id='name']","subject");
$I->fillField("//form[contains(@action,'http://eshop.lo/contact.html')]/fieldset/div/div/textarea[@id='message']","my messageee!");
$I->click('button[action="http://eshop.lo/contact.html"]');

$I->seeElement('.alert.alert-success');
$I->see('Your message has been sent');








