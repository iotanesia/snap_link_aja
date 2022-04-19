<?php

namespace App\Patners;

use Illuminate\Support\Facades\Http;

class Bri {

    const host = 'https://sandbox.partner.api.bri.co.id';
    public static function getAccessToken($param)
    {
        dd($param);
        try {
            $response = Http::timeout(5)
            ->withHeaders([
                'X-SIGNATURE' => $param['signature'],
                'X-CLIENT-KEY' => $param['id_key'],
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
