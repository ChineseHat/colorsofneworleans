<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application(array(
	'debug' => true
));

$config = require __DIR__ . '/../config.php';
foreach ($config as $key => $value)
{
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
    
  ));
});
echo "<!--";
$response = $app['twitter']->search->tweets('#saints');
$responses = $response->toValue()->statuses;
foreach ($responses as $index => $item) {
  print_r($item);
}
echo "-->";


$app['mustache'] = $app->share(function() {
	return new \Mustache_Engine(array(
		'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__ . '/templates', array('extension' => 'mustache')),
	));
});

$app->get('/', function() use ($app) {
	$template = $app['mustache']->loadTemplate('tweet');
	return $template->render();
});

$app->run();
