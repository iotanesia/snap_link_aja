<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Constants\Snap;
use Illuminate\Support\Facades\Log;
use App\ApiHelper as Helper;
use App\Models\ResponseCode;
use App\Constants\ErrorCode AS EC;
use Illuminate\Support\Facades\File;
use App\Patners\Bri as Patner;
class Bri {

    public static function authenticate($request)
    {
        try {
            $date = Carbon::now()->toIso8601String();
            // $date = gmdate("Y-m-d\TH:i:s.000\Z");
            // dd($date);
            $private_key = Storage::get('private.key');
            // dd($private_key);
            $plaintext = Snap::CLIENT_ID."|".$date;
            Log::info("plaintext: ".$plaintext);
            $binary_signature="";
            openssl_sign($plaintext, $binary_signature, $private_key, Snap::RSA_TYPE);
            $param = [
                'signature' => base64_encode($binary_signature),
                'timestamp' => $date,
                'id_key' => Snap::CLIENT_ID
            ];

            // $pubkeyid = Storage::get('public.key');
            // // $tes = openssl_verify($plaintext, base64_decode($param['signature']), $pubkeyid, Snap::RSA_TYPE);
            // // dd($tes);
            $response = Patner::getAccessToken($param);
            dd($response);
            return ['signature' => base64_encode($binary_signature), 'timestamp' => $date];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
