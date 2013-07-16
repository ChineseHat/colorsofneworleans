# Colors of New Orleans Twitter Aggregator #

## Installation ##
* Copy config/config.php-sample to config.php
* Create a MySQL database and include your database credentials in config/config.php.
* Import tables.sql into your database to define initial structure and state.
* Add your twitter credentials to config/config.php
* Install dependancies via composer.

```$ php composer.phar install```

* Perform your typical hosting environment installation steps for php applications.

## Populating the database ##
There is currently no administrative access to manage hashtags that will be aggregated. The database supports the creation of collections of hashtags. Collections can have many hashtags associated with them. For example, the Food collection currently only contains the #nolafood hashtag, but the collection could also include the #jazzfestfood hashtag (if it even exists).

The collections that drive the different tabs need to be manually created. This is due to a BINARY datatype for a MD5 hash that cannot be exported via mysqldump easily. The hash allows the cron.php script to constantly add new hashtags without creating duplicates.

```
INSERT INTO collections (name) VALUES ('Home');
SET @collection_id = LAST_INSERT_ID();
INSERT INTO hashtags (hashtag, hashtag_md5) VALUES ('nola', UNHEX(MD5('nola')));
SET @hashtag_id = LAST_INSERT_ID();
INSERT INTO hashtag_collections (collection_id, hashtag_id) VALUES(@collection_id, @hashtag_id);

INSERT INTO collections (name) VALUES ('Food');
SET @collection_id = LAST_INSERT_ID();
INSERT INTO hashtags (hashtag, hashtag_md5) VALUES ('nolafood', UNHEX(MD5('nolafood')));
SET @hashtag_id = LAST_INSERT_ID();
INSERT INTO hashtag_collections (collection_id, hashtag_id) VALUES(@collection_id, @hashtag_id);

INSERT INTO collections (name) VALUES ('Sports');
SET @collection_id = LAST_INSERT_ID();
INSERT INTO hashtags (hashtag, hashtag_md5) VALUES ('nolasaints', UNHEX(MD5('nolasaints')));
SET @hashtag_id = LAST_INSERT_ID();
INSERT INTO hashtag_collections (collection_id, hashtag_id) VALUES(@collection_id, @hashtag_id);

INSERT INTO collections (name) VALUES ('Music');
SET @collection_id = LAST_INSERT_ID();
INSERT INTO hashtags (hashtag, hashtag_md5) VALUES ('nolamusic', UNHEX(MD5('nolamusic')));
SET @hashtag_id = LAST_INSERT_ID();
INSERT INTO hashtag_collections (collection_id, hashtag_id) VALUES(@collection_id, @hashtag_id);

INSERT INTO collections (name) VALUES ('Festivals');
SET @collection_id = LAST_INSERT_ID();
INSERT INTO hashtags (hashtag, hashtag_md5) VALUES ('mardigras', UNHEX(MD5('mardigras')));
SET @hashtag_id = LAST_INSERT_ID();
INSERT INTO hashtag_collections (collection_id, hashtag_id) VALUES(@collection_id, @hashtag_id);

INSERT INTO collections (name) VALUES ('Community');
```

As of now, the front end serves out tweets that have been aggregated into the database. In order to populate/update the database with tweets, you need to run the cron job.

```$ php cron.php```
