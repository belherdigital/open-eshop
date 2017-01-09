# Kohana-Cron

This module provides a way to schedule tasks (jobs) within your Kohana application. Based on the job done by [Chris Bandy][https://github.com/cbandy/kohana-cron]

Uses vendor [mtdowling/cron-expression][https://github.com/mtdowling/cron-expression].


## Installation

Step 1: Download the module into your modules subdirectory.

Step 2: Create table crontab

    CREATE TABLE IF NOT EXISTS  `crontab` (
      `id_crontab` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(50) NOT NULL,
      `period` varchar(50) NOT NULL,
      `callback` varchar(140) NOT NULL,
      `params` varchar(255) DEFAULT NULL,
      `description` varchar(255) DEFAULT NULL,
      `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `date_started` datetime  DEFAULT NULL,
      `date_finished` datetime  DEFAULT NULL,
      `date_next` datetime  DEFAULT NULL,
      `times_executed`  bigint DEFAULT NULL,
      `output` varchar(50) DEFAULT NULL,
      `running` tinyint(1) NOT NULL DEFAULT '0',
      `active` tinyint(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id_crontab`),
      UNIQUE KEY `crontab_UK_name` (`name`)
    ) ENGINE=MyISAM DEFAULT;

Step 3: Enable the module in your bootstrap file:

	/**
	 * Enable modules. Modules are referenced by a relative or absolute path.
	 */
	Kohana::modules(array(
		'cron'       => MODPATH.'cron',
		// 'auth'       => MODPATH.'auth',       // Basic authentication
		// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
		// 'database'   => MODPATH.'database',   // Database access
		// 'image'      => MODPATH.'image',      // Image manipulation
		// 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
		// 'pagination' => MODPATH.'pagination', // Paging of results
		// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	));

## Usage

Add in the crontab table new entries to execute your code.

Edit bootstrap.php add at the end the following:
`Cron::run();`

This will run the cron on every request (not recommended, chec advanced).

## Advanced Usage

If you have access to the system crontab, you can run Cron once a minute (or less). You will need to modify the lines where the request is handled in your bootstrap file to prevent extraneous output. The default is:

	
    echo Request::factory()
            ->execute()
            ->send_headers()
            ->body();

Change it to:

    if ( ! defined('SUPPRESS_REQUEST'))
    {
        echo Request::factory()
            ->execute()
            ->send_headers()
            ->body();
    }

Then set up a system cron job to run your application's Cron once a minute:

* * * * * /usr/bin/php -f /var/www/open-classifieds/oc/modules/common/modules/cron/cron.php

The included `cron.php` should work for most cases (review path), but you are free to call `Cron::run()`
in any way you see fit.