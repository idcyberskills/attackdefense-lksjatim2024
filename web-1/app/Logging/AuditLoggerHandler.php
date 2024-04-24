<?php

namespace App\Logging;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;

class AuditLoggerHandler extends AbstractProcessingHandler
{
    public function write(array $record): void
    {
        $message = $record['message'];
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $context = '';
        
        $query = "INSERT INTO audit_logs (remote_addr, user_agent, message, context) VALUES ('$remote_addr', '$user_agent', '$message', '$context')";
        // var_dump($query);
        DB::insert($query);
    }
}