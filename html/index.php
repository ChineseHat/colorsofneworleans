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

$app['db'] = $app->share(function() use ($app) {
	return new \PDO(
		'mysql:host=' . $app['db_host'] . ';dbname=' . $app['db_name'],
		$app['db_user'],
		$app['db_pass']
	);
});

$app['mustache'] = $app->share(function() {
	return new \Mustache_Engine(array(
		'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__ . '/../templates', array('extension' => 'tpl')),
	));
});

$app->get('/{cat}', function() use (Application $app, Request $request, $cat) {

	$hastags = array(
		'sports' => 'nolastaints',
		'food' => '#nolafood',
		'community' => '#nola',
		'music' => '#nolamusic',
		'festivals' => '#mardigras',
		);

	$template = $app['mustache']->loadTemplate('test');
	return $template->render(array('planet' => 'World'));
});

$app->run();
