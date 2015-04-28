<?php

namespace Mworx\VehicleService\Controllers;

use Mworx\VehicleService\Entities\Vehicle;
use Symfony\Component\HttpFoundation\JsonResponse;

class VehicleController {

    protected $vehicle;

    public function __construct(Vehicle $vehicle) {
    }

    public function findById($id = 99) {
        return $id;
    }
}

