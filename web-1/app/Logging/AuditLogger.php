<?php

namespace App\Logging;

use Monolog\Logger;

class AuditLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        return new Logger(
            env('APP_NAME'),
            [
                new AuditLoggerHandler(),
            ]
        );
    }
}
