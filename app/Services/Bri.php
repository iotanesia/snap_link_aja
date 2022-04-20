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
            $date = substr(Carbon::now()->format('Y-m-d\TH:i:s.u'),0,23).'+07:00';
            $private_key = Storage::get('private.key');
            $stringToSign = Snap::CLIENT_ID."|".$date;
            Log::info("plaintext: ".$stringToSign);
            $binary_signature="";
            openssl_sign($stringToSign, $binary_signature, $private_key, 'SHA256');
            $signature =base64_encode($binary_signature);
            $param = [
                'signature' => self::hex64($signature),
                'timestamp' => $date,
                'id_key' => Snap::CLIENT_ID
            ];
            $response = Patner::getAccessToken($param);
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function hex64($signature) {
        return bin2hex(base64_decode($signature));;
    }

    static function hex_to_base64($hex){
        $return = '';
        foreach(str_split($hex, 2) as $pair){
          $return .= chr(hexdec($pair));
        }
        return base64_encode($return);
    }
}
