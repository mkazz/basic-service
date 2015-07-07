<?php

namespace MKaczorowski\BasicService\Entities;

class BaseEntity {

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
}

