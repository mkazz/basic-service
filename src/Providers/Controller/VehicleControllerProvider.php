<?php

namespace Mworx\VehicleService\Providers\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class VehicleControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/{id}', function ($id) use ($app) {
            return $app['vehicle.controller']->findById($id);
        });
        $controller->get('/test', function () { return "Howdy"; });

        return $controller;
    }
}

