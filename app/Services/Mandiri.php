<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Constants\Snap;
use Illuminate\Support\Facades\Log;
use App\ApiHelper as Helper;
use App\Patners\Mandiri as Patner;

class Mandiri {

    public static function authenticate($request)
    {
        try {
            $date = Helper::getDateNow();
            $private_key = Storage::get(config('services.mandiri.key').'.key');
            $stringToSign = Snap::CLIENT_ID_MANDIRI."|".$date;
            Log::info("plaintext: ".$stringToSign);
            $binary_signature="";
            openssl_sign($stringToSign, $binary_signature, $private_key, 'SHA256');
            $signature =base64_encode($binary_signature);
            $param = [
                'signature' => $signature,
                'timestamp' => $date,
                'id_key' => Snap::CLIENT_ID_MANDIRI
            ];
            $response = Patner::getAccessToken($param);
            $response['timestamp'] = $date;
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static function hex64($signature) {
        return bin2hex(base64_decode($signature));
        // return bin2hex($signature);
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
                // 'timeStamp' => '2022-05-24T23:00:59.672+07:00',
                'timeStamp' => $auth['timestamp'],
                'url' => $url,
                'request' => $request,
                'token' => $auth['accessToken']
                // 'token' => 'eyJraWQiOiJzc29zIiwiYWxnIjoiUlM1MTIifQ.eyJzdWIiOiJzeXM6ZGVmYXVsdEFwcGxpY2F0aW9uIiwiYXVkIjpbInN5czpkZWZhdWx0QXBwbGljYXRpb24iLCJqd3QtYXVkIl0sImNsaWVudElkIjoiMDQzNTI1NzEtMTZmOS00ZWU4LWE2NTQtYjZhZTA2YmQ4ZWM2Iiwic2lnbiI6IlgtUEFSVE5FUi1JRCIsImlzcyI6Imp3dC1pc3N1ZXIiLCJwYXJ0bmVySWQiOiJVQVRDT1JQQVkiLCJleHAiOjE2NTM0MDgwMTQsImlhdCI6MTY1MzQwNzExNH0.g763E4eXmPzKIwOk_ycIpSxv05evSJt5GoyFQKsTv-STojmrv2Lj77aTrTgx_385MyZlpx_nqRJm8O1nQEF6JFXOmhL7_zQ1xl-vWAYoTz_o1RSMa3g0sx7Q9D2JfDQgBqjZn3KVa9idQprxvPtaowW1seApGfmAy6IOX_WYFiAeEKTExXuibbo8qgP1UC_aDX4fImpXaFd_enoUxf0uYNTb5ZAZBAgk_n_w_NpGZtTFkc4m1N9WA9jQ8a2weF6dFeauzGi9qwLYbn3Qq346LjaySl-cUMN0DujOUJj_LaofZmQEHtlZTseBqwHaS_OVBbqbay_T9xm4TOEy2eiuDw'
            ];
            $secondSignature = self::generateSecondSignature($params);
            // $signature = base64_encode(md5((hash_hmac('sha512', $secondSignature, utf8_encode(snap::CLIENT_SECRET_MANDIRI))), true));
            $signature = base64_encode(((hash_hmac('sha512', $secondSignature, snap::CLIENT_SECRET_MANDIRI, true))));

            dd($secondSignature, $signature);

            $param = [
                'signature' => $signature,
                'externalId' => 95201095162054880,
                'partnerId' => Snap::PATNER_ID_MANDIRI,
                'auth' => $params['token'],
                'channelId' => 87899,
                'body' => $request->all(),
                'timestamp' => $params['timeStamp'],
                'url' => $url
            ];

            return Patner::snapService($param);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function generateSecondSignature($param)
    {
        $body = $param['request']->all();
        $minify = str_replace('null', '""', json_encode($body));
        $hexstring = strtolower(hash('sha256', $minify));
        // dd($param['request']->getMethod().':'.$param['url'].':'.$param['token'].':'.(string) $hexstring.':'.$param['timeStamp']);
        $payload = $param['request']->getMethod().':'.$param['url'].':'.$param['token'].':'.(string) $hexstring.':'.$param['timeStamp'];
        return $payload;
    }
}
