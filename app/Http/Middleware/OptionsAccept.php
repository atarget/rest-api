<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;


class OptionsAccept {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if ($request->isMethod('OPTIONS')) {
            $response = app('Illuminate\Http\Response');
            /** @var \Illuminate\Http\Response $response */
            $response->withHeaders([
                "Access-Control-Allow-Methods" => "GET, PUT, POST, HEAD, OPTIONS, DELETE",
                "Access-Control-Allow-Origin" => "*",
                "Access-Control-Allow-Headers" => "Content-type, Authorization, x-token, x-lang, x-xsrf-token, x-requested-with",
            ]);
            $response->setStatusCode(200);
            $response->setContent("ok");
            return $response;
        }
        $response = $next($request);
        /** @var Response $response */
        $response->header("Access-Control-Allow-Origin" , "*");
        $response->header("Access-Control-Allow-Headers", "Content-type, Authorization, x-token, x-lang, x-xsrf-token");
        $response->header("Access-Control-Allow-Methods", "GET, PUT, POST, HEAD, OPTIONS, DELETE");
        return $response;
    }
}
