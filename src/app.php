<?php

use Silex\Application;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

use Mworx\VehicleService\Controllers;
use Mworx\VehicleService\Providers;

$app = new Application();

//Register Service Providers
$app->register(new DoctrineServiceProvider());

//Register Controller Providers
$app['vehicle.controller'] = $app->share(function () {
    return new Controllers\VehicleController();
});

$app->mount('/vehicle', new Providers\Controller\VehicleControllerProvider());

return $app;

