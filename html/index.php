<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

$app = new Silex\Application(); 

$app['mustache'] = $app->share(function() {
	return new \Mustache_Engine;
});

$app->get('/', function() use ($app) { 
	return $app['mustache']->render('Hello {{planet}}', array('planet' => 'World!'));
}); 

$app->run(); 
