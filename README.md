# Colors of New Orleans Twitter Aggregator #

## Installation ##
* Copy config/config.php-sample to config.php
* Create a MySQL database and include your database credentials in config/config.php.
* Import tables.sql into your database to define initial structure and state.
* Add your twitter credentials to config/config.php
* Install dependancies via composer.
    $ php composer.phar install
* Perform your typical hosting environment installation steps for php applications.

## Populating the database ##
As of now, the front end serves out tweets that have been aggregated into the database. In order to populate/update the database with tweets, you need to run the cron job.

$ php cron.php
