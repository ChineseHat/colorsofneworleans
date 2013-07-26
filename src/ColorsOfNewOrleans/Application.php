<?php

namespace ColorsOfNewOrleans;

class Application extends \Silex\Application
{
    public function __construct(array $values = array())
    {
        $values['debug'] = true;

        parent::__construct($values);

        $app = $this;

        $app['db'] = $app->share(function () use ($app) {
            return new \PDO(
                "mysql:host={$app['db_host']};dbname={$app['db_name']}",
                $app['db_user'],
                $app['db_pass']
            );
        });

        $app['twitter-model'] = $app->share(function() use ($app) {
            return new Models\Twitter($app['db']);
        });

        $app['mustache'] = $app->share(function() {
            return new \Mustache_Engine(array(
                'loader' => new \Mustache_Loader_FilesystemLoader(
                    __DIR__ . '/../../templates',
                    array(
                        'extension' => 'mustache',
                    )
                ),
            ));
        });

        $app->get('/{collection}', function(\Silex\Application $app, $collection) {
            $tweets = $app['twitter-model']->getCollection($collection);
            $template = $app['mustache']->loadTemplate('tweet');
            return $template->render(array(
                'collection' => strtolower($collection),
                'items' => $tweets
            ));
        });

        $app->get('/', function(\Silex\Application $app) {
            $tweets = $app['twitter-model']->getTweets();
            $template = $app['mustache']->loadTemplate('tweet');
            return $template->render(array(
                'collection' => 'home',
                'items' => $tweets,
                'google_analytics_id' => $app['google_analytics_id']
            ));
        });
    }
}
