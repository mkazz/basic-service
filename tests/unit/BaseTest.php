<?php

namespace MKaczorowski\BasicService\Tests;

use MKaczorowski\BasicService\BaseApplication;
use MKaczorowski\BasicService\Providers;
use Silex\Provider\DoctrineServiceProvider;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {

    protected
        $app;

    public function setUp() {
        $this->app = require __DIR__."/../Jig/app.php";
        $db_config = $this->app['config']->db();
        $db_config['dbname'] =  $db_config['dbname'];
        $this->app->register(new DoctrineServiceProvider(), ['db.options' => $db_config]);
        $this->dbal = $this->app['db'];
    }
}
