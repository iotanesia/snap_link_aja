<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\ApiHelper;

class RSAController extends Controller
{
    //mitra link aja
    public function rsa()
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
            $date = Carbon::now()->toIso8601String();
            $private_key = Storage::get('private.key');
            // $plaintext = "2022-04-10T18:18:40+07:00|b0c75d7e09c54a8c9398395bb8ccb8ff";
            $plaintext = $date."|b0c75d7e09c54a8c9398395bb8ccb8ff";
            $algo = "RSA-SHA256";
            $binary_signature="";
            openssl_sign($plaintext, $binary_signature, $private_key, $algo);
            // print(base64_encode($binary_signature) ."\n");
            // print($plaintext);

           // return base64_encode($binary_signature);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //link aja
    public function verify_rsa(Request $request) {
        // $signature = self::rsa();
        // $timeStampIso = $request->header('X-TIMESTAMP');
        $timeStampIso = Carbon::now()->toIso8601String();
        $plaintext = $timeStampIso."|".$request->header('X-CLIENT-KEY');
        $signature = $request->header('X-SIGNATURE');
        $pubkeyid = Storage::get('public.key');

        $checkSignature = openssl_verify($plaintext, base64_decode($signature), $pubkeyid, 'RSA-SHA256');

        if($checkSignature) {
            // dd(true);
            $token = ApiHelper::createJwt($request->all());
            return ApiHelper::responseData($token);

        } else {
            // $token = ApiHelper::createJwt($request->all());
            dd(false);
        }

    }

    public function verify_rsa_test(Request $request) {
        $timeStampIso = Carbon::now()->toIso8601String();
        $private_key = Storage::get('private.key');
        // $plaintext = "2022-04-10T18:18:40+07:00|b0c75d7e09c54a8c9398395bb8ccb8ff";
        $plaintexts = $timeStampIso."|b0c75d7e09c54a8c9398395bb8ccb8ff";
        $algo = "RSA-SHA256";
        $binary_signature="";
        openssl_sign($plaintexts, $binary_signature, $private_key, $algo);

        // $timeStampIso = $request->header('X-TIMESTAMP');
        $plaintext = $timeStampIso."|".$request->header('X-CLIENT-KEY');
        $signature = base64_encode($binary_signature);
        $pubkeyid = Storage::get('public.key');

        $test = openssl_verify($plaintext, base64_decode($signature), $pubkeyid, 'RSA-SHA256');

        if($test) {
            dd(true);

        } else {
            dd(false);
        }
    }
}
