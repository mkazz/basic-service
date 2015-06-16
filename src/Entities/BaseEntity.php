<?php

namespace MKaczorowski\BasicService\Entities;

class BaseEntity {

    public function __construct($data_array) {
        foreach ($data_array as $field => $value) {
            $this->$field = $value;
        }
    }
}

