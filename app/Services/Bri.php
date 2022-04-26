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
use Illuminate\Support\Str;

class Bri {

    public static function authenticate($request)
    {
        try {
            $date = Helper::getDateNow();
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
            $response['timestamp'] = $date;
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

    public static function access($request, $url)
    {
        try {
            $auth = self::authenticate($request);
            $params = [
                'timeStamp' => $auth['timestamp'],
                'url' => $url,
                'request' => $request,
                'token' => $auth['accessToken']
            ];
            $secondSignature = self::generateSecondSignature($params);
            $param = ['signature' => hash_hmac('sha512', $secondSignature, snap::CLIENT_SECRET),
                      'externalId' => rand(0,999999999),
                      'partnerId' => 90890,
                      'auth' => $params['token'],
                      'channelId' => 87899,
                      'body' => $request->all(),
                      'timestamp' => $params['timeStamp'],
                      'url' => $url
                     ];
            return Patner::accountInquiryInternal($param);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function generateSecondSignature($param)
    {
        $body = $param['request']->all();
        $minify = json_encode($body);
        $hexstring = strtolower(hash('sha256', $minify));
        $payload = $param['request']->getMethod().':'.$param['url'].':'.$param['token'].':'.(string) $hexstring.':'.$param['timeStamp'];
        return $payload;
    }
}
