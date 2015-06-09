<?php

use Silex\Application;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

use Mworx\BasicService\Providers;

$app = new Application();

$app->register(
    new Providers\Service\ConfigurationServiceProvider()
);

$app->register(
    new DoctrineServiceProvider(),
    ['db.options' => $app['config']->db()]
);

return $app;

