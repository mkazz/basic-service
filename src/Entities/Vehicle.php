<?php

namespace Mworx\VehicleService\Entities;

class Vehicle {

    private
        $id,
        $vin,
        $make_id,
        $model_id,
        $mileage,
        $price,
        $engine_id,
        $transmission_id,
        $drivetrain_id,
        $exterior_color,
        $interior_color,
        $description_id,
        $created,
        $modified,
        $trim_id,
        $dbal,
    ;

    public function __construct() {
    }

}
