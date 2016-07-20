<?php

namespace MKaczorowski\BasicService\Entities;

abstract class BaseEntity {

    protected
        $_LABEL,
        $_LABEL_PLURAL,
        $_has_one,
        $_has_many,
        $_belongs_to_many;

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
