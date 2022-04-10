<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\User as Service;
use App\Services\Signature;
use App\Helper\Chilkat\Config as Chilkat;
use Illuminate\Support\Facades\File;

class AuthControler extends Controller
{
    use Chilkat;
    public function login(Request $request)
    {
        return ResponseInterface::responseData(
            Service::authenticateuser($request)
        );
    }


}
