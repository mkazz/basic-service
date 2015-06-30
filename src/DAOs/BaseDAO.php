<?php

namespace MKaczorowski\BasicService\DAOs;

class BaseDAO {

    protected 
        $entity,
        $dbal,
        $error;

    public function __construct($dbal, $entity) {
        $this->dbal   = $dbal;
        $this->entity = $entity;
    }

    public function getError() {
        return $this->error;
    }
}

