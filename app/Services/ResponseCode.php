<?php

namespace App\Services;

use App\ApiHelper as Helper;
use App\Models\ResponseCode as Model;

class ResponseCode {

    public static function retriveSlug()
    {
        $key = "response retrive";
        return Helper::storageCache($key,function () {
            return Model::get()->map(function ($item){
                return $item->slug;
            });
        });
    }

    public static function code($slug)
    {
        $key = "response code data {$slug}";
        return Helper::storageCache($key,function () use ($slug){
            $result = Model::where('slug',$slug)->first();
            return $result ? $result->http_code.$result->service_code.$result->case_code : null;
        });

    }

    public static function httpCode($slug)
    {
        $key = "response http code {$slug}";
        return Helper::storageCache($key,function () use ($slug){
            $result = Model::where('slug',$slug)->first();
            return $result->http_code ?? null;
        });

    }

    public static function message($slug)
    {
        $key = "response message {$slug}";
        return Helper::storageCache($key,function () use ($slug){
            $result = Model::where('slug',$slug)->first();
            return $result->message ?? null;
        });
    }

    public static function description($slug)
    {
        $key = "response description {$slug}";
        return Helper::storageCache($key,function () use ($slug){
            $result = Model::where('slug',$slug)->first();
            return $result->description ?? null;
        });
    }

    public static function serviceCode($slug)
    {
        $key = "response service code {$slug}";
        return Helper::storageCache($key,function () use ($slug){
            $result = Model::where('slug',$slug)->first();
            return $result->service_code ?? null;
        });
    }

    public static function caseCode($slug)
    {
        $key = "response case code {$slug}";
        return Helper::storageCache($key,function () use ($slug){
            $result = Model::where('slug',$slug)->first();
            return $result->case_code ?? null;
        });
    }

}
