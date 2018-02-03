<?php

namespace MKaczorowski\BasicService\Tests\Jig\DAOs;

use MKaczorowski\BasicService\DAOs\BaseDAO;

class JigDAO extends BaseDAO {
  public function __construct($dbal, $model, $table_name = "basicservice") {
    parent::__construct($dbal, $model, $table_name);
  }
}
