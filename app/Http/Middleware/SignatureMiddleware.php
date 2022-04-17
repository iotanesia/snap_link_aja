<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Signature;
use App\Services\ResponseCode as ServicesResponseCode;
class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if(!$request->header('x-client-key')) throw new \Exception(
                ServicesResponseCode::message('invalid-mandatory-field-field-name'),
                ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            );
            if(!$request->header('x-signature')) throw new \Exception(
                ServicesResponseCode::message('invalid-mandatory-field-field-name'),
                ServicesResponseCode::httpCode('invalid-mandatory-field-field-name')
            );
            if(!Signature::verified($request)) throw new \Exception(
                ServicesResponseCode::message('unauthorized-reason'),
                ServicesResponseCode::httpCode('unauthorized-reason')
            );
            return $next($request);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
