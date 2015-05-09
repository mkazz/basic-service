<?php

namespace Mworx\VehicleService\Controllers;

use Silex\Application;
use Mworx\VehicleService\Entities\Vehicle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VehicleController {

    public function findById(Application $app, Request $request) {
        $vehicle = $app['vehicle_factory'];
        $id = $request->get('id');
        return new JsonResponse($vehicle->findById($id));
    }
}

