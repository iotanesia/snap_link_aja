<?php

namespace App\Http\Controllers\Api\Bri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\Bri;
use App\Services\Signature;
class BriController extends Controller
{
    public function signatureAuth(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::authenticate($request)
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function signatureVerif(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Signature::verified($request)
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
