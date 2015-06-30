<?php

namespace MKaczorowski\BasicService\Entities;

class BaseEntity {

    public function load($data_array) {
        foreach ($data_array as $field => $value) {
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }
    }
}

