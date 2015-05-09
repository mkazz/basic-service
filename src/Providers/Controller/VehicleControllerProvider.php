<?php

namespace Mworx\VehicleService\Providers\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class VehicleControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];
        $controller->get('/{id}', 'Mworx\VehicleService\Controllers\VehicleController::findById');
        return $controller;
    }
}

