<?php

namespace Mworx\VehicleService\Services;

class ConfigurationService {

    private static $db_config = [];

    public function db() {
        if (empty(self::$db_config)) {
            self::$db_config = [
                'db_host'   => getenv('DB_HOST'),
                'db_name'   => getenv('DB_NAME'),
                'db_driver' => getenv('DB_DRIVER'),
                'db_user'   => getenv('DB_USER'),
                'db_pass'   => getenv('DB_PASS'),
            ];

        }

        return self::$db_config;
    }
}

