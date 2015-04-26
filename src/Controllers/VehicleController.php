<?php

namespace Mworx\VehicleService\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

class VehicleController {

    protected $vehicle;

    public function __construct() {
    }

    public function findById($id = 99) {
        return $id;
    }
}

