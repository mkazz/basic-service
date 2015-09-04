<?php

namespace MKaczorowski\BasicService\Entities;

abstract class BaseEntity {

    protected 
        $_LABEL,
        $_LABEL_PLURAL;

    private 
        $_fk_map;

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

    public function getLabel() {
        return $this->_LABEL;
    }
}

