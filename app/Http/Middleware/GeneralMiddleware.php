<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\ApiHelper as Helper;
use App\Constants\ErrorCode as EC;
use App\Constants\ErrorMessage as EM;
class GeneralMiddleware
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

            $content_type = $request->header('content-type');
            $content_type = $request->header('content-type');
            if(!in_array($content_type,['application/json'])) throw new \Exception("Unauthorized", 401);

            if(!$request->header('x-timestamp')) throw new \Exception("Bad Request", 400);
            if(!$request->header('private-key')) throw new \Exception("Unauthorized", 401);
            if(!$request->header('x-client-key')) throw new \Exception("Unauthorized", 401);
            return $next($request);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
