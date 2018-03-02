<?php

namespace MKaczorowski\BasicService\Tests\Jig\Models;

use MKaczorowski\BasicService\Models\BaseModel;
use MKaczorowski\BasicService\Tests\Jig\DAOs;

class JigModel extends BaseModel {

  public
    $id,
    $name;

  public function __construct($app) {
      parent::__construct($app);
      $this->dao = new DAOs\JigDAO($app['db'], $this);
  }
}
