<?php

namespace MKaczorowski\BasicService\Jig;

use MKaczorowski\BasicService\Tests\Jig\Models;
use MKaczorowski\BasicService\Tests\Jig\Providers;

use MKaczorowski\BasicService\BaseApplication;


$app = new BaseApplication();

// Models
$app->register(new Providers\Model\JigModelProvider());

// Controllers
//$app->mount('jigs', new Providers\Controller\JigControllerProvider());

return $app;
