<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\User as Service;

class AuthControler extends Controller
{
    public function login(Request $request)
    {
        return ResponseInterface::responseData(
            Service::authenticateuser($request)
        );
    }
}
