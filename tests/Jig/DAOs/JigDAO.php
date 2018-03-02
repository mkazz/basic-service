<?php

namespace MKaczorowski\BasicService\Tests\Jig\DAOs;

use MKaczorowski\BasicService\DAOs\BaseDAO;

class JigDAO extends BaseDAO {
  public function __construct($dbal, $model, $table_name = "jigs") {
    parent::__construct($dbal, $model, $table_name);
  }
}
