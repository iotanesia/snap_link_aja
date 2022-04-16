<?php

namespace App\Services;

use App\ApiHelper as Helper;
use App\Models\RequestService as Model;


class RequestService {

    public static function validationPayload($request)
    {
        try {
            $service = Model::where('url',$request->header('EndpointUrl'))->first();
            $requestBody = json_decode($service->request_body,true);
            $request_param = [];
            foreach ($requestBody as $param => $val) {
                if(!$request->$param) $request_param[] = false;
            }
            if(!$service) throw new \Exception("endpoint not found", 400);
            $validation = [
                'method' => $service->method == $request->header('HttpMethod') ? true : false,
                'url' => $service ? true : false,
                'request_body' => in_array(false,$request_param) ? false : true // validation body request
            ];
            return $validation;
        } catch (\Throwable $th) {
            throw $th;
        }
    }





}
