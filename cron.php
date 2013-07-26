<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/config.php';

$app = new ColorsOfNewOrleans\Application($config);

$twitter_client = new \ZendService\Twitter\Twitter(array(
    'accessToken' => array(
        'token' => $app['twitter_access_token'],
        'secret' => $app['twitter_access_secret'],
        ),
    'oauth_options' => array(
        'username' => $app['twitter_username'],
        'consumerKey' => $app['twitter_consumerkey'],
        'consumerSecret' => $app['twitter_consumersecret'],
        ),
    'http_client_options' => array(
        'adapter' => '\Zend\Http\Client\Adapter\Curl',
        ),
));

$twitter_model = $app['twitter-model'];

$tags = $twitter_model->getHashtags();

foreach ($tags as $tag) {
    $response = $twitter_client->search->tweets($tag);
    $responses = $response->toValue()->statuses;
    foreach ($responses as $tweet) {
        $twitter_model->saveTweet($tweet);
    }
}
