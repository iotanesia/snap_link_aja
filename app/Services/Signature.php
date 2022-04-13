<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Constants\Snap;
use Illuminate\Support\Facades\Log;
use App\ApiHelper as Helper;
use Illuminate\Support\Facades\File;

class Signature {

    public static function create($request)
    {
        try {
            $date = Carbon::now()->toIso8601String();
            $private_key = Storage::get('private.key');
            $plaintext = $date."|".Snap::CLIENT_ID;
            Log::info("plaintext: ".$plaintext);
            $binary_signature="";
            openssl_sign($plaintext, $binary_signature, $private_key, Snap::RSA_TYPE);
            return [
                'signature' => base64_encode($binary_signature)
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function verified($request)
    {
        $createSignature = self::create($request);
        $timeStampIso = Carbon::now()->toIso8601String();
        $plaintext = $timeStampIso."|".$request->header('X-CLIENT-KEY');
        $signature = $createSignature['signature'];
        $pubkeyid = Storage::get('public.key');
        return openssl_verify($plaintext, base64_decode($signature), $pubkeyid, Snap::RSA_TYPE);
    }

    public static function generateToken($request)
    {
        try {
            if(!$request->grantType) throw new \Exception("Bad Request", 400);
            if($request->grantType != 'client_credentials') throw new \Exception("Bad Request", 400);
            Log::info(self::create($request)['signature']);

            $signature = self::create($request)['signature'];
            $token = Helper::createJwtSignature([
                'signature' => $signature
            ]);
            $second_signature = self::generateSecondSignature($request);
            dd($second_signature);
            return [
                'signature' => $signature,
                'token' => $token,
                'token_is_verified' => Helper::decodeJwtSignature($token,$signature) ? true : false,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getSignatureService($request)
    {
        try {
            $signature = self::create($request)['signature'];
            $token = Helper::createJwtSignature([
                'signature' => $signature
            ]);
            $payload = self::generateSecondSignature($request);
            dd($payload);
            return [
                'signature' => $signature,
                'token' => $token,
                'token_is_verified' => Helper::decodeJwtSignature($token,$signature) ? true : false,
                'payload' => '',
                'hmac' => '',
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function generateSecondSignature($request)
    {
        try {

            $payload = $request->header('HttpMethod').':'.$request->header('EndpointUrl').':'.$request->header('AccessToken').':'.(string) json_encode($request->all(),true).':'.$request->header('X-TIMESTAMP').':';
            return $payload;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
