<?php

namespace Mworx\VehicleService\Controllers;

use Silex\Application;
use Mworx\VehicleService\Entities\Vehicle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DebugController {

    public function dbInfo(Application $app, Request $request) {
        $config = $app['config'];
        return new JsonResponse($config->db());
    }

    public function testDbWrite(Application $app, Request $request) {
        $debug_entity = $app['debug_factory'];
        return new JsonResponse($debug_entity->testWrite($request->get('thing')));
    }

    public function testDbRead(Application $app, Request $request) {
        $debug_entity = $app['debug_factory'];
        return new JsonResponse($debug_entity->testRead());
    }
}

