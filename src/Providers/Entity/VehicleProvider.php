<?php

namespace Mworx\VehicleService\Providers\Entity;

use Mworx\VehicleService\Entities\Vehicle;
use Silex\Application;
use Silex\ServiceProviderInterface;

class VehicleProvider implements ServiceProviderInterface {

    public function register(Application $app) {
        $app['vehicle_factory'] = function () {
            return new Vehicle($app['dbs']);
        };
    }

    public function boot(Application $app) {
    }

}

