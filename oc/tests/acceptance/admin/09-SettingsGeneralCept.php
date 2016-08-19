<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('change general settings');

$I->login_admin();

/*************************
ENABLE MAINTENANCE MODE
**************************/

$I->amOnPage('/oc-panel/Config/update/maintenance');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->seeElement('.alert.alert-info');
$I->see('You are in maintenance mode, only you can see the website');

$I->amOnPage('/');
$I->seeElement('.alert.alert-info');
$I->see('You are in maintenance mode, only you can see the website');

// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

$I->amOnPage('/');
$I->seeElement('.jumbotron');
$I->see('We are working on our site, please visit later. Thanks');
$I->seeElement('.glyphicon.glyphicon-user');

$I->login_admin();

$I->amOnPage('/oc-panel/Config/update/maintenance');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');


/*************************
COOKIE CONSENT
**************************/

$I->amOnPage('/oc-panel/Config/update/cookie_consent');
$I->fillField('#formorm_config_value','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
//$I->seeElement('.fixed.bottom');
//$I->see('We use cookies to track usage and preferences');
//$I->seeElement('.cb-enable');
//$I->see('I Understand','a');

// Cookies consent appears, but phpBrowser can't read it. If I uncomment the above 4 lines, 
// test fails but on _output/admin.09-SettingsGeneralCept.fail.html cookie consent appears.

$I->amOnPage('/oc-panel/Config/update/cookie_consent');
$I->fillField('#formorm_config_value','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->dontSee('We use cookies to track usage and preferences');
$I->dontSeeElement('.cb-enable');
$I->dontSee('I Understand','a');


/*************************
LANDING PAGE
**************************/

$I->amOnPage('/oc-panel/Config/update/landing_page');
$I->fillField('formorm[config_value]','{"controller":"product","action":"listing"}');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->see('Listing','h1');

$I->amOnPage('/oc-panel/Config/update/landing_page');
$I->fillField('formorm[config_value]','{"controller":"home","action":"index"}');
$I->click('formorm[submit]');

$I->amOnPage('/');
$I->see('Latest','h2');


/*************************
SITE NAME + DESCRIPTION
**************************/

$I->amOnPage('/oc-panel/Config/update/site_name');
$I->fillField('formorm[config_value]','Site Name');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/site_description');
$I->fillField('formorm[config_value]','Site Description!!');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');


$I->amOnPage('/');
$I->see('Site Name','h1');
$I->amOnPage('/info.json');
$I->see('Site Description!!');


$I->amOnPage('/oc-panel/Config/update/site_name');
$I->fillField('formorm[config_value]','Test OE');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/site_description');
$I->fillField('formorm[config_value]','');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');


/*************************
ACTIVATE BLOG
**************************/

// Enable Blog
$I->amOnPage('/oc-panel/Config/update/blog');
$I->fillField('formorm[config_value]','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Create Blog Post
$I->amOnPage('/oc-panel/Blog/create');
$I->see('Create Blog Post','h1');

$I->fillField('#formorm_title','First Post');
$I->fillField('#formorm_description','This is the First Post');
$I->checkOption('formorm[status]');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Blog post created. Please to see the changes delete the cache');

// Enable disqus for blog
$I->amOnPage('/oc-panel/Config/update/blog_disqus');
$I->fillField('formorm[config_value]','eshoplo');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Read Blog
$I->amOnPage('/');
$I->see('Blog','a');
$I->amOnPage('/blog');
$I->see('Test OE Blog','h1');
$I->see('First Post','a');
$I->see('This is the First Post');
$I->amOnPage('/blog/first-post.html');
$I->see('First Post','h1');
$I->see('This is the First Post');
$I->seeElement('div', ['id' => 'disqus_thread']);

// Update Post
$I->amOnPage('/oc-panel/blog/index');
$I->click('//a[@class="btn btn-primary ajax-load"]');
$I->see('Edit Blog Post','h1');
$I->fillField('#formorm_title','First Post Updated');
$I->fillField('#formorm_description','This is the updated First Post');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Blog post updated. Please to see the changes delete the cache');

$I->amOnPage('/blog');
$I->see('First Post Updated','a');
$I->see('This is the updated First Post');
$I->amOnPage('/blog/first-post.html');
$I->see('First Post Updated','h1');
$I->see('This is the updated First Post');
$I->seeElement('div', ['id' => 'disqus_thread']);

// Delete Post
$I->amOnPage('/oc-panel/blog/index');
$I->click('//a[@class="btn btn-danger index-delete"]');

$I->amOnPage('/blog');
$I->dontSee('First Post Updated','a');
$I->amOnPage('/blog/first-post.html');
$I->dontSee('First Post Updated','h1');

// Disable disqus for blog
$I->amOnPage('/oc-panel/Config/update/blog_disqus');
$I->fillField('formorm[config_value]','');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Enable Blog
$I->amOnPage('/oc-panel/Config/update/blog');
$I->fillField('formorm[config_value]','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSee('Blog','a');


/*************************
ACTIVATE FAQ
**************************/

// Enable FAQ
$I->amOnPage('/oc-panel/Config/update/faq');
$I->fillField('formorm[config_value]','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Create FAQ
$I->amOnPage('/oc-panel/content/create?type=help');
$I->fillField('#title','How to');
$I->fillField('#description','How to use the internet');
$I->fillField('#seotitle','how-to');
$I->checkOption('status');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('help is created. Please to see the changes delete the cache');

// Enable disqus comments for FAQ
$I->amOnPage('/oc-panel/Config/update/faq_disqus');
$I->fillField('formorm[config_value]','eshoplo');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Read FAQ
$I->amOnPage('/');
$I->see('FAQ','a');
$I->amOnPage('/faq');
$I->see('Frequently Asked Questions','h1');
$I->seeElement('input', ['name' => 'search']);
$I->see('How to','a');
$I->seeElement('.faq-list');
$I->seeElement('div', ['id' => 'disqus_thread']);
$I->amOnPage('/faq/how-to.html');
$I->see('How to','h1');
$I->see('How to use the internet');

// Update FAQ
$I->amOnPage('/oc-panel/content/help');
$I->see('FAQ','h1');
$I->click('//a[@class="drag-action ajax-load"]');
$I->see('Edit FAQ','h1');
$I->fillField('#description','How to use the internet updated');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('help is edited');

$I->amOnPage('/faq/how-to.html');
$I->see('How to','h1');
$I->see('How to use the internet updated');

// Disable disqus comments for FAQ
$I->amOnPage('/oc-panel/Config/update/faq_disqus');
$I->fillField('formorm[config_value]','');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Delete FAQ
$I->amOnPage('/oc-panel/content/help');
$I->click('//a[@class="drag-action index-delete"]');

// Disable FAQ
$I->amOnPage('/oc-panel/Config/update/faq');
$I->fillField('formorm[config_value]','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSee('FAQ','a');
$I->amOnPage('/faq/how-to.html');
$I->dontSee('How to','h1');

$I->amOnPage('/oc-panel/Config/update/forums');
$I->fillField('formorm[config_value]','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');


/*************************
ACTIVATE FORUMS
**************************/
/*
// Enable forums
$I->amOnPage('/oc-panel/Config/update/forums');
$I->fillField('formorm[config_value]','1');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

// Create forum
$I->amOnPage('/oc-panel/forum/create');
$I->see('New Forum','h1');
$I->fillField('#name','My forum');
$I->fillField('#description','My forum description');
$I->fillField('#seoname','my-forum');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Forum is created.');

// Create topic
$I->amOnPage('/oc-panel/Topic/create');
$I->see('New Topic','h1');
$I->selectOption('formorm[id_forum]','1');
$I->fillField('#formorm_title','My Topic');
$I->fillField('#formorm_description','My Topic Description');
$I->checkOption('formorm[status]');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item created. Please to see the changes delete the cache');

// Read Forum-Topic
$I->amOnPage('/');
$I->see('Forums','a');
$I->amOnPage('/forum');
$I->see('Forums','h1');
$I->see('MY FORUM','a');
$I->amOnPage('/forum/my-forum');
$I->see('My forum','h1');
$I->see('My forum Description','p');
$I->see('MY TOPIC','a');
$I->amOnPage('/forum/my-forum/my-topic.html');
$I->see('My Topic','h1');
$I->seeElement('.thumbnail.highlight');
$I->see('Reply','a');
$I->see('My Topic Description','p');

// Reply to the topic
$I->amOnPage('/forum/my-forum/my-topic.html');
$I->fillField('//textarea[@name="description"]','This is my reply');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Reply added, thanks!');

// Update Forum
$I->amOnPage('/oc-panel/forum/index');
$I->click('//a[@class="drag-action ajax-load"]');
$I->see('Edit Forum');
$I->fillField('#name','My favorite forum');
$I->fillField('#description','My favorite forum Description');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Forum is updated.');

// Update Topic
$I->amOnPage('/oc-panel/Topic/update/3');
$I->fillField('#description','This is my updated reply');
$I->click('submit');

$I->seeElement('.alert.alert-success');
$I->see('Topic is updated.');

// Read updated forum and topic
$I->amOnPage('/forum');
$I->see('MY FAVORITE FORUM','a');
$I->amOnPage('/forum/my-forum');
$I->see('My favorite forum','h1');
$I->see('My favorite forum Description','p');
$I->amOnPage('/forum/my-forum/my-topic.html');
//$I->see('This is my updated reply');

// Delete forum and then disable forums
$I->amOnPage('/oc-panel/forum/index');
$I->click('//a[@class="drag-action index-delete"]');

$I->amOnPage('/oc-panel/Config/update/forums');
$I->fillField('formorm[config_value]','0');
$I->click('formorm[submit]');

$I->seeElement('.alert.alert-success');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSee('Forums','a');

*/


// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

