<?php

$app['db'] = $app->share(function () use ($app) {
    return new PDO(
        "mysql:dbname={$app['db_name']}",
        $app['db_user'],
        $app['db_pass']
    );
});

function addTweet ($row) {
    global $app;
    
}

function getTweets () {
    global $app;
    $sql = "SELECT * FROM tweets";
    foreach ($app['db']->query($sql) as $row) {
        print_r($row);
    }
}

function getHashtagTweets($name) {
    global $app;
    $sql = "
SELECT t.tweet_id
     , t.created_at
     , t.text
     , h.hashtag
  FROM tweets AS t
INNER 
  JOIN hashtags AS h
    ON h.id = t.hashtag
 WHERE h.hashtag = $name";
    $results = $app['db']->query($sql);
    foreach ($results as $result) {
        print_r($result);
    }
}



