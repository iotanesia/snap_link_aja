<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Models\responseCode;
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
        $result = Signature::generateToken($request);
        // return ResponseInterface::resultResponse($result);
        return ResponseInterface::responseDataSnap(null, 200, 01, $request->header('x-signature'), $result['token']);
    }

    public function service(Request $request)
    {
        return ResponseInterface::resultResponse(
            Signature::getSignatureService($request)
        );
    }

    public function generateResponseLabel(Request $request)
    {
        //  dd(ServicesResponseCode::httpCode('successful'));
        dd(ServicesResponseCode::retriveSlug());
    }
}
