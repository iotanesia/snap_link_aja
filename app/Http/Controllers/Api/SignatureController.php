<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Models\responseCode;
use App\Services\RequestService;
use App\Services\ResponseCode as Http;
use App\Services\Signature;
use Illuminate\Support\Str;
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

    public function generateToken(Request $request)
    {
        // return ResponseInterface::resultResponse($result);
        return ResponseInterface::responseDataSnap('successful', $request->header('x-signature'), Signature::generateToken($request)['token']);
    }

    public function service(Request $request)
    {
        return ResponseInterface::resultResponse(
            Signature::getSignatureService($request)
        );
    }

    public function cardValidation(Request $request)
    {
        return ResponseInterface::resultResponse(
            Signature::cardValidation($request)
        );
    }

    public function generateResponseLabel(Request $request)
    {
        try {
            if(!$request->header('channel-id')) throw new \Exception("Invalid Mandatory CHANNEL-ID", 400);
            if(!$request->header('x-external-id')) throw new \Exception("Invalid Mandatory X-EXTERNAL-ID", 400);
            if(!$request->header('x-partner-id')) throw new \Exception("Invalid Mandatory X-PARTNER-ID", 400);
            if(in_array(false,RequestService::validationPayload($request))) throw new \Exception("Invalid Signature", 401);
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

    public function signatureValidation(Request $request)
    {
        $data = Signature::verifiedSecondSignature($request);
    }
}
