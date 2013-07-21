<?php

$app['db'] = $app->share(function () use ($app) {
    return new PDO(
        "mysql:host={$app['db_host']};dbname={$app['db_name']}",
        $app['db_user'],
        $app['db_pass']
    );
});

function saveTweet ($data) {
    global $app;
    //Add new users. Duplicates will be ignored.
    $userSql = "INSERT INTO users VALUES (
        NULL,
        {$data->user->id_str}, 
        '{$data->user->name}', 
        '{$data->user->screen_name}', 
        '{$data->user->profile_image_url}'
    )";
    $app['db']->exec($userSql);
    $user_id = $app['db']->lastinsertid();

    //Add the tweet
    $timestamp = strtotime($data->created_at);
    $tweetSql = "INSERT INTO tweets VALUES (
        NULL,
        {$data->id_str}, 
        {$user_id},
        FROM_UNIXTIME($timestamp), 
        '{$data->text}'
    )";
    $app['db']->exec($tweetSql);
    $tweet_id = $app['db']->lastinsertid();

    //Add new hashtags. Duplicates will be ignored.
    foreach ($data->entities->hashtags as $tag) {
        $hashtag = strtolower($tag->text);
        $hashtagSql = "INSERT INTO hashtags VALUES (
            NULL,
            '$hashtag',
            UNHEX(MD5('$hashtag'))
        )";
        $app['db']->exec($hashtagSql);
        $tweetTagSql = "INSERT INTO tweet_hashtags VALUES (
            {$tweet_id},
            (SELECT id FROM hashtags AS h WHERE h.hashtag = '$hashtag')
        )";
        $app['db']->exec($tweetTagSql);
    }
    
};

function getCollection ($name) {
    global $app;

    $sql = "
SELECT GROUP_CONCAT(t.id SEPARATOR ', ') AS tweet_ids
  FROM tweets AS t
INNER
  JOIN tweet_hashtags AS th
    ON th.tweet_id = t.id
INNER
  JOIN hashtag_collections AS hc
    ON hc.hashtag_id = th.hashtag_id
INNER
  JOIN collections AS c
    ON c.id = hc.collection_id
 WHERE LOWER(c.name) = LOWER('$name')
";
    $res = $app['db']->query($sql);
    $row = $res->fetch();
    $where_clause = " WHERE t.id IN ({$row['tweet_ids']})";
    //get stuff
    return getTweets($where_clause);
}

function getTweets ($where_clause = '') {
    global $app;
    
    $sql = "
SELECT t.twitter_id
     , t.created_at
     , t.text
     , GROUP_CONCAT(h.hashtag SEPARATOR ' ') AS hashtags
     , u.twitter_user_id
     , u.name
     , u.screen_name
     , u.profile_image_url
     , GROUP_CONCAT(LOWER(c.name) SEPARATOR ' ') AS collections
  FROM tweets AS t
INNER
  JOIN users AS u
    ON u.id = t.user_id
INNER
  JOIN tweet_hashtags AS th
    ON th.tweet_id = t.id
INNER
  JOIN hashtags AS h
    ON h.id = th.hashtag_id
INNER
  JOIN hashtag_collections AS hc
    ON hc.hashtag_id = th.hashtag_id
INNER
  JOIN collections AS c
    ON c.id = hc.collection_id
$where_clause
GROUP
    BY t.twitter_id
     , t.created_at
     , t.text
     , u.twitter_user_id
     , u.name
     , u.screen_name
     , u.profile_image_url
ORDER
    BY t.created_at DESC
 LIMIT 30
";
    $tweets = array();
    foreach ($app['db']->query($sql) as $tweet) {
        $tweets[] = $tweet;
    }   
    return $tweets;
}
