<?php

use Silex\Application;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

use Mworx\VehicleService\Controllers;
use Mworx\VehicleService\Providers;

$app = new Application();

//Read Configuration
$app->register(new Providers\Service\ConfigurationServiceProvider()); //some config provider

//Register Service Providers

$app->register(
    new DoctrineServiceProvider(),
    ['db.options' => $app['config']->db()]
);

$app->register(new Providers\Entity\VehicleProvider());
$app->register(new Providers\Entity\DebugProvider());

//Register Controller Providers
$app->mount('/vehicle', new Providers\Controller\VehicleControllerProvider());
$app->mount('/debug', new Providers\Controller\DebugControllerProvider());

return $app;

