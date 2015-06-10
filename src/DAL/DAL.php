<?php

namespace Mworx\BasicService\DAL;

class DAL {

    protected $dbal;

    public function __construct($dbal) {
        $this->dbal = $dbal;
    }

}

