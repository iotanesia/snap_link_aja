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
        $content_type = $request->header('content-type');
        if(!in_array($content_type,['application/json'])) return Helper::unauthorizedResponse(EC::UNAUTHORIZED,EM::FORBIDDEN);
        return $next($request);
    }
}
