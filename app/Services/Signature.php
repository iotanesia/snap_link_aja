<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Constants\Snap;
use Illuminate\Support\Facades\Log;

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
        $signature = base64_encode($createSignature['signature']);
        $pubkeyid = Storage::get('public.key');
        return openssl_verify($plaintext, base64_decode($signature), $pubkeyid, Snap::RSA_TYPE);
    }

}
