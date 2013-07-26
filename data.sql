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
