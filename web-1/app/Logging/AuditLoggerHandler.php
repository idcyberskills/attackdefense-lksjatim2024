<?php

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;

class AuditLoggerHandler extends AbstractProcessingHandler
{
    public function write(array $record): void
    {
        Log::info($record['message']);
        Log::info(json_encode($record));
        dd(json_encode($record));
    }
}