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
use App\Patners\Mti as Patner;
class Mti {

    const Username = '64e67320-7f58-48a9-9196-1d2cdff30fb2';
    const Password = '97a30baf-25a5-47e6-9e51-5a3420f0f369';
    public static function authenticate($request)
    {
        try {
            $date = Carbon::now()->format('Y-m-d\ H:i:s');
            $stringToSign = self::Username."|".$date;
            $param = [
                'signature' => hash_hmac('sha512',$stringToSign,self::Password),
                'timestamp' => $date,
                'id_key' => self::Username
            ];
            dd($param);
            $response = Patner::getAccessToken($param);
            dd($response);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
