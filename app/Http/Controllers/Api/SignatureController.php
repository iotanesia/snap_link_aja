<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Models\responseCode;
use App\Services\RequestService;
use App\Services\ResponseCode as Http;
use App\Services\ResponseCode as ServicesResponseCode;
use App\Services\Signature;
use Illuminate\Support\Str;
class SignatureController extends Controller
{
    // mitra
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

    public function generateToken(Request $request)
    {
        $token =  Signature::generateToken($request)['token'];
        return ResponseInterface::resultResponse(
            [
                'responseCode' => Http::code('successful'),
                'responseMessage' => Http::message('successful'),
                "accessToken" => $token,
                "tokenType" => "Bearer",
                "expiresIn" => ResponseInterface::decodeJwtSignature($token,$request->header('x-signature'))->exp ?? null,
            ]
        );
    }

    // mitra
    public function service(Request $request)
    {
        return ResponseInterface::resultResponse(
            Signature::getSignatureService($request)
        );
    }


    public function cardValidation(Request $request)
    {
        try {
            if(in_array(false,RequestService::validationPayload($request))) throw new \Exception(
                ServicesResponseCode::message('unauthorized-reason'),
                ServicesResponseCode::httpCode('unauthorized-reason')
            );
            return ResponseInterface::resultResponse(
                [
                    'responseCode' => Http::code('successful'),
                    'responseMessage' => Http::message('successful'),
                    'data' => $request->all()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
