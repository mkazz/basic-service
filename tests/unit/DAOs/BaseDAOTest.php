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

    private function truncate() {
      $this->dbal->executeQuery("TRUNCATE TABLE jigs");
    }

    private function newDao() {
      return new JigDAO($this->app['db'], $this->app['jig_model'], 'jigs');
    }

    private function newModel() {
      $model = $this->app['jig_model'];
      $model->name = "find_by_id_test" . rand(1,100000);
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
      $this->assertEquals($model->id, $result['id'] );
      $this->assertEquals($model->name, $result['name']);
    }

    public function testFindByIdNull() {
      $dao = $this->newDao();
      $this->expectException(\TypeError::class);
      $result = $dao->findById(null);
    }

    public function testFindByIdInvalidString() {
      $dao = $this->newDao();
      $this->expectException(\TypeError::class);
      $result = $dao->findById("thisisnotright99");
    }

    public function testFindByIdValidString() {
      $dao = $this->newDao();
      $model = $this->newModel();
      $id = (string) $model->id;
      $result = $dao->findById($id);
      $this->assertEquals($model->id, $result['id']);
    }

    public function testFindAll(){
      $this->truncate();
      for ($i = 0; $i < 3; $i++){
        $this->newModel();
      }
      $dao = $this->newDao();
      $result = $dao->findAll();
      $this->assertCount(3, $result);
      $this->truncate();
    }

    public function testFindAllBy() {
      $this->truncate();
      $dao = $this->newDao();
      $model = $this->newModel();
      $model->name = "bob1";
      $model->save();
      $model = $this->newModel();
      $model->name = "bob1";
      $model->save();
      $model = $this->newModel();
      $model->name = "bob2";
      $model->save();
      $result = $dao->findAllBy('name', 'bob2');
      $this->assertCount(1, $result);
      $result = $dao->findAllBy('name', 'bob1');
      $this->assertCount(2, $result);
      $this->truncate();
    }

    public function testFindAllByInvalidField() {
      $dao = $this->newDao();
      $this->expectException(
        \MKaczorowski\BasicService\Exceptions\InvalidFieldException::class
      );
      $result = $dao->findAllBy('not_a_real_field', 'plumbus');
      $result = $dao->findAllBy(null, "poop");
    }
}
