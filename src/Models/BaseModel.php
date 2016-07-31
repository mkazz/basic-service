<?php

namespace MKaczorowski\BasicService\Models;

use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseModel {

    protected
        $_LABEL,
        $_LABEL_PLURAL,
        $_has_one,
        $_has_many,
        $_belongs_to_many,
        $app,
        $dao,
        $constraints = [];

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function fetchRelations($entity) {
        $relations = [];
        if (empty($entity) || empty($entity->getRelations())) {
          return $entity;
        }

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

    public function fetchHasMany($entity) {
        $has_many = [];
        if (empty($entity->has_many)) {
          return $entity;
        }

        foreach ($entity->has_many as $key => $join_table) {
            $model = $this->app["{$key}_model_factory"];
            $label = $entity->getLabel();
            $related_entities = $model->findAllByParent($label, $entity->id);
            if (!empty($related_entities)) {
                $has_many[$related_entities[0]->getLabel(true)] = $related_entities;
            }
        }
        $entity->loadRelations($has_many);
        return $entity;
    }

    public function findAll() {
        return $this->dao->findAll();
    }

    public function findAllByParent($parent, $parent_id) {
        return $this->dao->findAllByParent($parent, $parent_id);
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

    public function findAllByLike($field, $value) {
        return $this->dao->findAllByLike($field, $value);
    }

    public function findAllByLikeWithParent(
      $field,
      $value,
      $parent,
      $parent_name) {
        $parent_object = $this->app["{$parent}_model_factory"]->findBy('name', $parent_name);
        if (!empty($parent_object)) {
          return $this->dao->findAllByLikeWithParent(
            $field,
            $value,
            $parent,
            $parent_object->id
          );
        }
      return false;
    }

    public function findAllByOperator($field, $value, $operator, $value2) {
        return $this->dao->findAllByOperator($field, $value, $operator, $value2);
    }

    public function findAllBy($field, $value) {
        return $this->dao->findAllBy($field, $value);
    }

    public function save(&$entity) {
        $this->load($entity);
        if (!$this->isValid()) {
          return false;
        }
        $result = $this->dao->save($entity);
        $this->error = $this->dao->getError();
        return $result;
    }

    protected function isValid() {
      $this->errors = $this->app['validator']->validate(
        $this);

      foreach ($this->errors as $error) {
        var_dump($error->getPropertyPath() . " " . $error->getMessage() . "\n");
      }
      die();
      return (!count($errors) > 0) ? true : false;
    }

    public function __get($name) {
        switch ($name) {
          case 'has_many':
            return $this->_has_many;
          case 'has_and_belongs_to_many':
            return $this->_has_and_belongs_to_many;
          case 'has_one':
            return $this->_has_one;
          default:
            return null;
        }

        return null;
    }

    public function load($data_array) {
        foreach ($data_array as $field => $value) {
            if ($this->isFieldValid($field)) {
                $this->$field = $value;
            }
        }
    }

    public function loadRelations($relations) {
        foreach ($relations as $label => $data) {
            $this->$label = $data;
        }
    }

    public function isFieldValid($field) {
        return property_exists($this, $field);
    }

    public function isFieldReadOnly($field) {
        return in_array($field, $this->_read_only);
    }

    public function getLabel($plural = false) {
        return $plural == true ? $this->_LABEL_PLURAL : $this->_LABEL;
    }

    public function getRelations() {
        return $this->_has_one;
    }

    public function isParentValid($parent) {
        return array_key_exists($parent, $this->has_and_belongs_to_many);
    }

    public function getJoinTable($parent) {
        if ($this->isParentValid($parent)) {
            return $this->has_and_belongs_to_many[$parent];
        }
        return null; // or raise?
    }
}
