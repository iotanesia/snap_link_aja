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
                'timeStamp' => $auth['timestamp'],
                'url' => '/openapi'.$url,
                'request' => $request,
                'token' => $auth['accessToken']
            ];
            $secondSignature = self::generateSecondSignature($params);
            $signature = base64_encode(hash_hmac('sha512', $secondSignature, snap::CLIENT_SECRET_MANDIRI));

            // dd($signature);
            // dd([
            //     'payload' => $secondSignature,
            //     'signature' => $signature,
            //     'body' => json_encode($request->all()),
            // ]);

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
        // $minify = '{"partnerReferenceNo":"1205868175767876","amount":{"value":"110000.00","currency":"IDR"},"sourceAccountNo":"1150006399259","beneficiaryAccountNo":"60004400184","remark":"","transactionDate":"2022-05-24T11:15:35.920+07:00","beneficiaryEmail":"rendi.matrido1995@gmail.com","additionalInfo":{"reportCode":"","senderInstrument":"tunai","senderAccountNo":"08786561231224","senderName":"rendi matrido","senderCountry":"id","senderCostumerType":"1","beneficiaryAccountName":"","beneficiaryInstrument":"","beneficiaryCustomerType":""}}';
        // $hexstring = strtolower(self::hex64(hash('sha256', $minify)));
        $hexstring = strtolower(hash('sha256', $minify));
        dd($param['request']->getMethod().':'.$param['url'].':'.$param['token'].':'.(string) $hexstring.':'.$param['timeStamp']);
        $payload = $param['request']->getMethod().':'.$param['url'].':'.$param['token'].':'.(string) $hexstring.':'.$param['timeStamp'];
        return $payload;
    }
}
