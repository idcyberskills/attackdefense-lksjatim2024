<?php

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;

class AuditLoggerHandler extends AbstractProcessingHandler
{
    public function write(array $record): void
    {
        $data = @unserialize($record['message']);
        if ($data) {

        }
        else {

        }
    }
}