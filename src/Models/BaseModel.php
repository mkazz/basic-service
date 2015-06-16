<?php

namespace MKaczorowski\BasicService\Models;

use Silex\Application;

class BaseModel {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }
}

