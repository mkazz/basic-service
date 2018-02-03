<?php

namespace MKaczorowski\BasicService\Tests\DAOs;

use MKaczorowski\BasicService\BaseApplication;
use MKaczorowski\BasicService\Tests\BaseTest;
use MKaczorowski\BasicService\Tests\Jig\DAOs\JigDAO;

class BaseDAOTest extends BaseTest {

    protected
      $app,
      $dao,
      $dbal;

    public function testSave(){
      $dao = new JigDAO($this->dbal, new \StdClass(), 'jigs');
      $model = $this->app['jig_model'];
      $model->name = 'jiggy';
      $result = $dao->save($model);
      $this->assertTrue($result);
    }
}
