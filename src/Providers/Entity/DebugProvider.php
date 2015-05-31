<?php

namespace Mworx\VehicleService\Providers\Entity;

use Mworx\VehicleService\Entities\Debug;
use Silex\Application;
use Silex\ServiceProviderInterface;

class DebugProvider implements ServiceProviderInterface {

    public function register(Application $app) {
        $app['debug_factory'] = function () use($app) {
            return new Debug($app['db']);
        };
    }

    public function boot(Application $app) {
    }

}

