<?php

namespace MKaczorowski\BasicService;

use Silex\Application;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use MKaczorowski\BasicService\Providers;


class BaseApplication extends Application {

    public function __construct() {
        parent::__construct();
        $this->register(
            new Providers\Service\ConfigurationServiceProvider()
        );

        $this->register(
            new DoctrineServiceProvider(),
            ['db.options' => $this['config']->db()]
        );

        $this->register(new ValidatorServiceProvider());
    }
}
