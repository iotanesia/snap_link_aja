<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Models\responseCode;
use App\Services\RequestService;
use App\Services\ResponseCode as ServicesResponseCode;
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
        // dd(RequestService::validationPayload($request));
        // dd(ServicesResponseCode::retriveSlug());
        dd(Signature::verifiedSecondSignature($request));
    }

    public function signatureValidation(Request $request)
    {
        $data = Signature::verifiedSecondSignature($request);
    }
}
