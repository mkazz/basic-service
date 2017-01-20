<?php

namespace MKaczorowski\BasicService\Models;

use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;
Use MKaczorowski\BasicService\Exceptions as Exceptions;

abstract class BaseModel {

    protected
        $_LABEL,
        $_LABEL_PLURAL,
        $_has_one,
        $_has_many,
        $_belongs_to_many,
        $_read_only = [],
        $app,
        $dao,
        $constraints = [];

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function fetchRelations() {
        $relations = [];
        if (empty($this->getRelations())) {
          return;
        }
        foreach ($this->getRelations() as $key => $factory) {
            $model         = $this->app[$factory];
            $related_model = $model->findById($this->$key);
            if (isset($related_model)) {
                $relations[$related_model->getLabel()] = $related_model;
            }
        }
        $this->loadRelations($relations);
    }

    public function fetchHasMany() {
        $has_many = [];
        if (empty($this->has_many)) {
          return;
        }

        foreach ($this->has_many as $key => $join_table) {
            $model = $this->app[$key];
            $label = $this->getLabel();
            $related_entities = $model->findAllByParent($label, $this->id);
            if (!empty($related_entities)) {
                $has_many[$related_entities[0]->getLabel(true)] = $related_entities;
            }
        }
        $this->loadRelations($has_many);
    }

    public function findAll() {
        return $this->returnMany($this->dao->findAll());
    }

    public function findAllByParent($parent, $parent_id) {
        return $this->returnMany($this->dao->findAllByParent($parent, $parent_id));
    }

    public function findById($id) {
        return $this->returnOne($this->dao->findById($id));
    }

    public function findBy($field, $value) {
        return $this->returnOne($this->dao->findBy($field, $value));
    }

    public function findAllByLike($field, $value) {
        return $this->returnMany($this->dao->findAllByLike($field, $value));
    }

    public function findAllByLikeWithParent(
      $field,
      $value,
      $parent,
      $parent_name) {
        $parent_object = $this->app[$parent]->findBy('name', $parent_name);
        if (!empty($parent_object)) {
          return $this->returnMany($this->dao->findAllByLikeWithParent(
            $field,
            $value,
            $parent,
            $parent_object->id
          ));
        }
      return false;
    }

    public function findAllByOperator($field, $value, $operator, $value2) {
        return $this->returnMany($this->dao->findAllByOperator($field, $value, $operator, $value2));
    }

    public function findAllBy($field, $value) {
        return $this->returnMany($this->dao->findAllBy($field, $value));
    }

    public function save(&$entity) {
        $this->load($entity);    
        if (!$this->isValid()) {
          throw new Exceptions\ValidationException($this->errors);
        }
        $result = $this->dao->save($entity);
        $this->error = $this->dao->getError();
        return $result;
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
        return property_exists($this, $field) && !is_object($this->$field);
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

    public function isValid() {
      $this->errors = $this->app['validator']->validate($this);
      return (count($this->errors) > 0) ? false : true;
    }

    protected function returnMany($data) {
        if ($data === null || empty($data)) {
          return;
        }
        try {
          $objects = [];
          foreach ($data as $row) {
            $object = $this->app[$this->_LABEL];
            $object->load($row);
            $objects[] = $object;
          }
          return $objects;
        } catch (\Exception $e) {
         $this->error = $e->getMessage();
         return false;
        }
    }

    protected function returnOne($data) {
      if ($data === null || empty($data)) {
        return;
      }

      try {
        $this->load($data);
        return $this;
      } catch (\Exception $e) {
        $this->error = $e->getMessage();
      }
      return false;
    }
}
