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
            return ['signature' => base64_encode($binary_signature), 'x_timestamp' => $date];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function verified($request)
    {
        $timeStampIso = $request->header('x-timestamp');
        $plaintext = $timeStampIso."|".$request->header('X-CLIENT-KEY');
        $signature = $request->header('x-signature');
        $pubkeyid = Storage::get('public.key');
        return openssl_verify($plaintext, base64_decode($signature), $pubkeyid, Snap::RSA_TYPE);
    }

    public static function generateToken($request)
    {
        try {
            if(!$request->grantType) throw new \Exception("Bad Request", 400);
            if($request->grantType != 'client_credentials') throw new \Exception("Bad Request", 400);
            Log::info($request->header('x-signature'));
            $signature = $request->header('x-signature');
            $token = Helper::createJwtSignature([
                'signature' => $signature
            ]);
            return [
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
            $signature = $request->header('x-signature');
            $token = Helper::createJwtSignature([
                'signature' => $signature
            ]);
            $payload = self::generateSecondSignature($request);
            $hmacs = hash_hmac('sha512', $payload, $request->header('x-client-secret'));
            return [
                'signature' => $signature,
                'token' => $token,
                'token_is_verified' => Helper::decodeJwtSignature($token,$signature) ? true : false,
                'payload' => $payload,
                'hmac' => $hmacs,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function generateSecondSignature($request)
    {
        $payload = $request->header('HttpMethod').':'.$request->header('EndpointUrl').':'.$request->header('AccessToken').':'.(string) json_encode($request->all(),true).':'.$request->header('X-TIMESTAMP');
        return $payload;
    }

    public static function cardValidation($request)
    {
        try {
            if(!$request->header('method')) throw new \Exception("Bad Request", 400);
            if(!$request->header('endpoint')) throw new \Exception("Bad Request", 400);
            if(!$request->header('x-client-secret')) throw new \Exception("Bad Request", 400);
            if(!$request->header('x-timestamp')) throw new \Exception("Bad Request", 400);

            

        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
