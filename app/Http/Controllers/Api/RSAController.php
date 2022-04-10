<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RSAController extends Controller
{
    public function rsa()
    {
        date_default_timezone_set('Asia/Jakarta');

        try {
           // $date = Carbon::now()->toIso8601String();
            $private_key = Storage::get('private.key');

            $plaintext = "2022-04-10T18:18:40+07:00|b0c75d7e09c54a8c9398395bb8ccb8ff";
            $algo = "RSA-SHA256";
            $binary_signature="";
            openssl_sign($plaintext, $binary_signature, $private_key, $algo);
            print(base64_encode($binary_signature) ."\n");

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function verify_rsa(){

    }
}
