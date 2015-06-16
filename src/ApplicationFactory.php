<?php

namespace MKaczorowski\BasicService;

use Silex\Application;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use MKaczorowski\BasicService\Providers;


class ApplicationFactory {

    public function __construct() {
        $app = new Application();
        $app->register(
            new Providers\Service\ConfigurationServiceProvider()
        );

        $app->register(
            new DoctrineServiceProvider(),
            ['db.options' => $app['config']->db()]
        );
        return $app;
    }
}

