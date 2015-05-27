<?php

use Silex\Application;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

use Mworx\VehicleService\Controllers;
use Mworx\VehicleService\Providers;

$app = new Application();

//Read Configuration
$app->register(); //some config provider

//Register Service Providers
$app->register(new DoctrineServiceProvider());
$app->register(
    new Providers\Entity\VehicleProvider()
);

//Register Controller Providers
$app->mount('/vehicle', new Providers\Controller\VehicleControllerProvider());

return $app;

