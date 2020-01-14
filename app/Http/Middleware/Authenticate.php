<?php

namespace App\Http\Middleware;

use App\Api\Service\Service;
use Closure;
use Illuminate\Support\Str;

class Authenticate {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header("Authorization");
        if ($token) {
            $token = Str::substr($token, 7);
        }
        if (!$token) {
            $token = $request->get("jwt");
        }
        Service::user()->setJWT($token);
        return $next($request);
    }
}
