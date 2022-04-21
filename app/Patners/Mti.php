<?php

namespace App\Patners;

use Illuminate\Support\Facades\Http;
use App\Constants\Snap;
class Mti {

    const host = 'https://dev.yokke.co.id:7778';
    public static function getAccessToken($param)
    {
        try {
            $response = Http::timeout(0)
            ->withHeaders([
                'X-MTI-KEY' => $param['id_key'],
                'X-SIGNATURE' => $param['signature'],
                'X-TIMESTAMP' => $param['timestamp']
            ])
            ->contentType("application/json")
            ->get(self::host.'/directDebit/getjwt');
            dd($response->getStatusCode());
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
