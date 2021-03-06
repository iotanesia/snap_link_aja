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
use stdClass;

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
                'accessToken' => $token,
                'tokenType' => 'Bearer',
                'expiresIn' => (string) ResponseInterface::decodeJwtSignature($token,$request->header('x-signature'))->exp ?? null,
                'additionalinfo' => null
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
            $addObj = new stdClass;
            //   dd($request->getContent());

            // if(!$request->header('channel-id')) throw new \Exception(
            //     ServicesResponseCode::message('invalid-mandatory-field-field-name'),
            //     ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            // );
            // if(!$request->header('x-external-id')) throw new \Exception(
            //     ServicesResponseCode::message('invalid-mandatory-field-field-name'),
            //     ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            // );
            // if(!$request->header('x-partner-id')) throw new \Exception(
            //     ServicesResponseCode::message('invalid-mandatory-field-field-name'),
            //     ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            // );
            if(in_array(false,RequestService::validationPayload($request))) throw new \Exception(
                ServicesResponseCode::message('unauthorized-reason'),
                ServicesResponseCode::httpCode('unauthorized-reason')
            );
            return ResponseInterface::resultResponse(
                [
                    'responseCode' => Http::code('successful'),
                    'responseMessage' => Http::message('successful'),
                    'additionalInfo' => $addObj,
                    // 'data' => $request->all()
                    'data' => json_decode(json_decode($request->getContent(), false), false)
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function cardBindLimit(Request $request)
    {
        try {
            $addObj = new stdClass;
            //   dd($request->getContent());

            // if(!$request->header('channel-id')) throw new \Exception(
            //     ServicesResponseCode::message('invalid-mandatory-field-field-name'),
            //     ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            // );
            // if(!$request->header('x-external-id')) throw new \Exception(
            //     ServicesResponseCode::message('invalid-mandatory-field-field-name'),
            //     ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            // );
            // if(!$request->header('x-partner-id')) throw new \Exception(
            //     ServicesResponseCode::message('invalid-mandatory-field-field-name'),
            //     ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            // );
            if(in_array(false,RequestService::validationPayload($request))) throw new \Exception(
                ServicesResponseCode::message('unauthorized-reason'),
                ServicesResponseCode::httpCode('unauthorized-reason')
            );
            return ResponseInterface::resultResponse(
                [
                    'responseCode' => Http::code('successful'),
                    'responseMessage' => Http::message('successful'),
                    'referenceNo' => 'bdf4ed26e4ed43118e8e73ddc0115f6e',
                    'partnerReferenceNo' => '2020102900000000000001',
                    'additionalInfo' => $addObj
                    // 'data' => $request->all()
                    // 'data' => json_decode(json_decode($request->getContent(), false), false)
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
