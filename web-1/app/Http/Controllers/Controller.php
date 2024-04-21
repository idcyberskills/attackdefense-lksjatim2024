<?php

namespace App\Http\Controllers;

use App\Traits\IpCheck;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function recordSensitiveAction($action, $data, $message='', $content_type='') 
    {   
        if ($content_type == 'application/json') $data = (object)$data;

        $serialized = serialize([
            'action' => $action,
            'data' => $data,
            'message' => $message
        ]);

        var_dump($serialized);

        return Log::channel('audit')->info($serialized);
    }
}
