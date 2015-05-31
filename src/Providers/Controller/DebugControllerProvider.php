<?php

namespace Mworx\VehicleService\Providers\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class DebugControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];
        $controller->get('/db_info', 'Mworx\VehicleService\Controllers\DebugController::dbInfo');
        $controller->get('/write', 'Mworx\VehicleService\Controllers\DebugController::testDbWrite');
        $controller->get('/read', 'Mworx\VehicleService\Controllers\DebugController::testDbRead');
        return $controller;
    }
}

