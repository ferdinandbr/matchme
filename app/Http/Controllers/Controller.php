<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Log;

class Controller 
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseError($data, $code = 400)
    {
        return response()->json(['errors' => $data], $code);
    }

    public function responseSuccess($data, $code = 200)
    {
        return response()->json($data, $code);
    }
}
