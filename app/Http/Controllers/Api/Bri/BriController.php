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

    public function signatureService(Request $request)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function accountInquiryInternal(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::access($request, '/intrabank/snap/v1.0/account-inquiry-internal')
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function accountInquiryStatus(Request $request)
    {
        try {
            return ResponseInterface::resultResponse(
                Bri::access($request, '/snap/v1.0/transfer/status')
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
