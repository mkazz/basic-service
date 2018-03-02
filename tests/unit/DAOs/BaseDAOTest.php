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

    private function newDao() {
      return new JigDAO($this->app['db'], $this->app['jig_model'], 'jigs');
    }

    private function newModel() {
      $model = $this->app['jig_model'];
      $model->name = "find_by_id_test";
      $model->save();
      return $model;
    }

    public function testSave() {
      $dao = $this->newDao();
      $model = $this->app['jig_model'];
      $model->name = 'jiggy';
      $result = $dao->save($model);
      $this->assertTrue($result);
    }

    public function testFindById() {
      $dao = $this->newDao();
      $model = $this->newModel();
      $result = $dao->findById($model->id);
      $this->assertEquals($result['id'], $model->id);
      $this->assertEquals($result['name'], $model->name);
    }

    public function testFindByIdNull() {
      $dao = $this->newDao();
      $model = $this->newModel();
      $result = $dao->findById(null);
      $this->assertEquals($result['id'], $model->id);
      $this->assertEquals($result['name'], $model->name);
    }
}
