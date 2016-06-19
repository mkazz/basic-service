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

    public function fetchRelations($entity) {
        $relations = [];
        foreach ($entity->getRelations() as $key => $factory) {
            $model          = $this->app["{$factory}_model_factory"];
            $related_entity = $model->findById($entity->$key);

            if (isset($related_entity)) {
                $relations[$related_entity->getLabel()] = $related_entity;
            }
        }
        $entity->loadRelations($relations);
        return $entity;
    }

    public function findAll() {
        return $this->dao->findAll();
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

