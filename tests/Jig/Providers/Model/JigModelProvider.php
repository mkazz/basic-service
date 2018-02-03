<?php

namespace MKaczorowski\BasicService\Tests\Jig\Providers\Model;

use MKaczorowski\BasicService\Tests\Jig\Models;
use MKaczorowski\BasicService\Tests\Jig\DAOs;
use Silex\Application;
use Silex\ServiceProviderInterface;

class JigModelProvider implements ServiceProviderInterface {

  public function register(Application $app) {
    $app['jig_model'] = function () use ($app) {
      $jig_model = new Models\JigModel($app);
      return $jig_model;
    };
  }

  public function boot(Application $app) {
  }
}
