<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

$app = new Silex\Application(array(
    'debug' => TRUE,
)); 

$config = require __DIR__ . '/../config.php';
foreach ($config as $key => $value)
{
	$app[$key] = $value;
}

require_once __DIR__ . '/../db.php'; 

getTweets('nola');


$app['mustache'] = $app->share(function() {
	return new \Mustache_Engine;
});

$app->get('/', function() use ($app) { 
	return $app['mustache']->render('Hello {{planet}}', array('planet' => 'World!'));
}); 

$app->run(); 
