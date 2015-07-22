<?php

namespace MKaczorowski\BasicService\Models;

use Silex\Application;

abstract class BaseModel {

    protected 
        $app,
        $dao,
        $entity_factory_key;

    public function __construct(Application $app, $entity_factory_key) {
        $this->app = $app;
        $this->entity_factory_key = $entity_factory_key;
    }

    public function findById($id) {
        $entity = $this->app[$this->entity_factory_key];
        $entity->id = $id;
        $entity = $this->dao->findById($entity);
        return $entity;
    }

    public function findBy($field, $value) {
        return $this->dao->findBy($field, $value);
    }

    public function findAllByOperator($field, $value, $operator, $value2) {
        return $this->dao->findAllByOperator($field, $value, $operator, $value2);
    }

    public function findAllBy($field, $value) {
        return $this->dao->findAllBy($field, $value);
    }

    public function save($entity) {
        $result = $this->dao->save($entity);
        $this->error = $this->dao->getError();
        return $result;
    }
}

