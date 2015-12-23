<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('check frontend');

$I->amOnPage('/');

// Top Menu
$I->seeElement('.navbar.navbar-default.navbar-fixed-top');
$I->see('Home','a');
$I->see('Listing','a');
$I->see('Search','a');
$I->see('Contact','a');
$I->seeElement('.glyphicon-home');
$I->seeElement('.glyphicon-list');
$I->seeElement('.glyphicon-search');
$I->seeElement('.glyphicon-envelope');
$I->seeElement('.glyphicon-signal');

// Homepage
$I->seeElement('a', ['href' => 'http://eshop.lo/']);
$I->see('Categories','h2');
$I->seeElement('input', ['placeholder' => 'Search']);
$I->see('Login','a');

// Listing
$I->amOnPage('/all');
$I->seeElement('.breadcrumb');
$I->see('Listing','h1');
$I->seeElement('a', ['id' => 'list']);
$I->seeElement('a', ['id' => 'grid']);
$I->seeElement('button', ['id' => 'sort']);
$I->see('We do not have any product in this category','h3');

// Search
$I->amOnPage('/search.html');
$I->see('Advanced Search','h1');
$I->seeElement('input', ['id' => 'search']);
$I->seeElement('select', ['id' => 'category']);
$I->seeElement('input', ['id' => 'price-min']);
$I->seeElement('input', ['id' => 'price-max']);
$I->seeElement('button', ['action' => 'http://eshop.lo/search.html']);

// Contact
$I->amOnPage('/contact.html');
$I->see('Contact Us','h1');
$I->see('Name','label');
$I->see('Email','label');
$I->see('Subject','label');
$I->see('Message','label');
$I->seeElement('input', ['id' => 'name']);
$I->seeElement('input', ['id' => 'email']);
$I->seeElement('input', ['id' => 'subject']);
$I->seeElement('textarea', ['id' => 'message']);
$I->seeElement('button', ['name' => 'submit']);

// RSS
$I->amOnPage('/rss.xml');
$I->see('Latest published');





















