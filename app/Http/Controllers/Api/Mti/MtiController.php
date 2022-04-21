<?php

namespace App\Http\Controllers\Api\Mti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\Mti;
class MtiController extends Controller
{
    public function signatureAuth(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Mti::authenticate($request)
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
