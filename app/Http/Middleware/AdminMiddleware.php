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
       try {
            $token = $request->header('x-api-token');
            if(!$token) throw new \Exception("Bad Request", 400);

            try {
                $credentials = Helper::decodeJwt($token);
            } catch(ExpiredException $e) {
                 throw new \Exception("unauthorized", 401);
            } catch(\Exception $e) {
                 throw new \Exception("unauthorized", 401);
            }

            $user = User::admin($credentials->sub->id);
            if (!$user['items']) throw new \Exception("unauthorized", 401);
            return $next($request);
       } catch (\Throwable $th) {
           throw $th;
       }
    }
}
