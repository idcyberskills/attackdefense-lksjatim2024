<?php

namespace App\Logging;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;

class AuditLoggerHandler extends AbstractProcessingHandler
{
    public function write(array $record): void
    {
        $log = new AuditLog();

        $log->context = '';
        $log->message = $record['message'];
        $log->remote_addr = $_SERVER['REMOTE_ADDR'];
        $log->user_agent = $_SERVER['HTTP_USER_AGENT'];

        $log->save();
    }
}