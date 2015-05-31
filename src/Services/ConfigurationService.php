<?php

namespace Mworx\VehicleService\Services;

class ConfigurationService {

    private static $db_config = [];

    public function db() {
        if (empty(self::$db_config)) {
            self::$db_config = [
                'host'     => getenv('DB_HOST'),
                'dbname'   => getenv('DB_SCHEMA'),
                'driver'   => getenv('DB_DRIVER'),
                'user'     => getenv('DB_USER'),
                'pass'     => getenv('DB_PASSWD'),
                'password' => getenv('DB_PASSWD'),
            ];

        }

        return self::$db_config;
    }
}

