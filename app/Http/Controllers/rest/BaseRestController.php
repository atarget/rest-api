<?php

namespace App\Http\Controllers\rest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class BaseRestController extends Controller {
    public static function routes(string $base, string $controller = null) {
        $controller = Str::replaceFirst("App\\Http\\Controllers\\", "", $controller);
        app()->router->get($base . "/{id}", "${controller}@get");
        app()->router->post($base . "/", "${controller}@save");
        app()->router->post($base . "/find", "${controller}@find");
        app()->router->delete($base . "/{id}", "${controller}@delete");
    }
}
