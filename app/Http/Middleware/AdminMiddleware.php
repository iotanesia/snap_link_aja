<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\ApiHelper as Helper;
use Firebase\JWT\ExpiredException;
use App\Constants\ErrorCode as EC;
use App\Constants\ErrorMessage as EM;
use App\Services\User;

class AdminMiddleware
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
        $token = $request->header('x-api-token');
        if(!$token) return Helper::createResponse(EC::INVALID_ACCESS_TOKEN,EM::TOKEN_NOT_FOUND);
        try {
            $credentials = Helper::decodeJwt($token);
        } catch(ExpiredException $e) {
            return Helper::createResponse(EC::INVALID_ACCESS_TOKEN,EM::INVALID_ACCESS_TOKEN);
        } catch(\Exception $e) {
            return Helper::createResponse(EC::INVALID_ACCESS_TOKEN,EM::ERROR_ACCESS_TOKEN);
        }

        $user = User::admin($credentials->sub->id);
        if (!$user['items']) return Helper::createResponse(EC::UNAUTHORIZED, EM::UNAUTHORIZED);
        return $next($request);
    }
}
