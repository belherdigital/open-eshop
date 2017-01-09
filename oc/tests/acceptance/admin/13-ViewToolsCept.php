<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('check if tools open');

$I->login_admin();

// Updates
$I->amOnPage('/oc-panel/update/index');
$I->see('Updates','h1');
$I->see('Your installation version is');
$I->see('Your Hash Key for this installation is');
$I->seeElement('.btn.btn-primary.pull-right.ajax-load');
$I->see('Berlin');
$I->see('2.7.0');
$I->see('Gant');

// Crontab
$I->amOnPage('/oc-panel/crontab/index');
$I->see('Crontab','h1');
$I->seeElement('.btn.btn-primary.pull-right.ajax-load');
$I->seeElement('.delete.btn.btn-danger');

// Sitemap
$I->amOnPage('/oc-panel/tools/sitemap');
$I->see('Sitemap','h1');
$I->seeElement('.btn.btn-primary.ajax-load');
$I->see('Last time generated');
$I->see('Your sitemap XML to submit to engines');
$I->seeElement('input', ['value' => 'http://eshop.lo/sitemap.xml.gz']);
$I->click('a[href="http://eshop.lo/oc-panel/tools/sitemap?force=1"]');
$I->seeElement('.alert.alert-success');
$I->see('Memory peak');
$I->see('Time');

// Optimize
$I->amOnPage('/oc-panel/tools/optimize');
$I->see('Optimize Database','h1');
$I->see('Database space');
$I->see('Space to optimize');
$I->seeElement('.btn.btn-primary.pull-right.ajax-load');

// Cache
$I->amOnPage('/oc-panel/tools/cache');
$I->see('Cache','h1');
$I->see('Cache configuration information.');
$I->seeElement('a', ['href' => 'http://eshop.lo/oc-panel/tools/cache?force=1']);
$I->seeElement('a', ['href' => 'http://eshop.lo/oc-panel/tools/cache?force=2']);
$I->click('a[href="http://eshop.lo/oc-panel/tools/cache?force=1"]');
$I->seeElement('.alert.alert-success');

// Logs
$I->amOnPage('/oc-panel/tools/logs');
$I->see('System Logs','h1');
$I->see('Reading log file');
$I->seeElement('button', ['class' => 'btn btn-primary']);

// PHP Info
$I->amOnPage('/oc-panel/tools/phpinfo');
$I->see('PHP Info');
$I->see('System');
$I->see('Server API');
$I->see('Virtual Directory Support');









// Logout
$I->amOnPage('/oc-panel/auth/logout');
$I->see('Login','a');

