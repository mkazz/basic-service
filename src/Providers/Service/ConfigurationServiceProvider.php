<?php

namespace Mworx\VehicleService\Providers\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Mworx\VehicleService\Services\ConfigurationService;

class ConfigurationServiceProvider implements ServiceProviderInterface {

    public function register(Application $app) {
        $app['config'] = $app->share(function () {
            return new ConfigurationService();
        });
    }

    public function boot(Application $app) {
    }
}

