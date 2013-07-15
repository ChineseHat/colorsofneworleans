<?php

require_once __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config/config.php';

$app = new Silex\Application(array(
    'debug' => true
));

foreach ($config as $key => $value) {
    $app[$key] = $value;
}

//Load database stuff
require_once __DIR__ . '/../db.php'; 

$app['twitter'] = $app->share(function() use ($app){
    return new ZendService\Twitter\Twitter(array(
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
});


$app['mustache'] = $app->share(function() {
    return new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__ . '/../templates', array('extension' => 'mustache')),
    ));
});

$app->get('/{collection}', function(Silex\Application $app, $collection) {
    $tweets = getCollection($collection);

    $template = $app['mustache']->loadTemplate('tweet');
    return $template->render(array("collection" => strtolower($collection), "items" => $tweets));
});

$app->get('/', function(Silex\Application $app) {
    $tweets = getTweets();

    $template = $app['mustache']->loadTemplate('tweet');
    return $template->render(array("collection" => "home", "items" => $tweets));
});

$app->run();
