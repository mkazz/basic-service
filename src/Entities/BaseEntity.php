<?php

namespace MKaczorowski\BasicService\Entities;

abstract class BaseEntity {

    protected $_LABEL;
    protected $_LABEL_PLURAL;

    public function load($data_array) {
        foreach ($data_array as $field => $value) {
            if ($this->isFieldValid($field)) {
                $this->$field = $value;
            }
        }
    }

    public function isFieldValid($field) {
        return property_exists($this, $field);
    }

    public function getLabel() {
        return $this->_LABEL;
    }
}

