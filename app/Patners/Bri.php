<?php

namespace App\Patners;

use Illuminate\Support\Facades\Http;
use App\Constants\Snap;
class Bri {

    const host = 'https://sandbox.partner.api.bri.co.id';
    public static function getAccessToken($param)
    {
        // dd($param['id_key']);
        try {
            $response = Http::timeout(5)
            ->withHeaders([
                'X-CLIENT-KEY' => 'h5KQRxkbd5PxQXD8QRgtf7PvLSsKqbuq',
                'X-SIGNATURE' => $param['signature'],
                'X-TIMESTAMP' => $param['timestamp']
            ])
            ->contentType("application/json")
            ->post(self::host.'/snap/v1.0/oauth-b2b/accesstoken',[
                'grantType' => 'client_credentials'
            ]);
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
