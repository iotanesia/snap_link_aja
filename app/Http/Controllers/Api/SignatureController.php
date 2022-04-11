<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\Signature;
class SignatureController extends Controller
{
    public function create(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Signature::create($request)
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function hello(Request $request)
    {
        return ResponseInterface::resultResponse(
            Signature::generateToken($request)
        );
    }
}
