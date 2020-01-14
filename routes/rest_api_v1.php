<?php

use App\Http\Controllers\rest\v1\UserController;
/** @var \Laravel\Lumen\Routing\Router $router */
$router->group([ 'prefix' => 'api/v1' ], function () use ($router) {
    UserController::routes("user");
    \App\Http\Controllers\rest\v1\TranslationController::routes("/translation");
    \App\Http\Controllers\rest\v1\LangController::routes("/lang");
    \App\Http\Controllers\rest\v1\MenuController::routes("/menu");
    /**{{{routes-anchor}}}**/
});
