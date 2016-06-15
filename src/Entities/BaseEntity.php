<?php

namespace MKaczorowski\BasicService\Entities;

abstract class BaseEntity {

    protected 
        $_LABEL,
        $_LABEL_PLURAL,
        $_fk_map;

    /* Sample FK map
    protected
        $_fk_map = [
            'some_foreign_key_field' => 'some_object',
            ...
            ...
            'some_other_key' => 'some_other_object'
        ];
     */

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

    public function getRelations() {
        return $this->_fk_map;
    }
}

