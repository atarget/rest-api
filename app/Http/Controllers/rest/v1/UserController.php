<?php

namespace App\Http\Controllers\rest\v1;

use App\Api\Exceptions\NotAuthentificated;
use App\Api\Service\Service;
use App\Http\Controllers\rest\BaseRestController;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserController extends BaseRestController {

    /**
     * @param string $base
     * @param null $controller
     */
    public static function routes(string $base, string $controller = null) {
        app()->router->get($base . "/rights", "rest\\v1\\UserController@rights");
        app()->router->get($base . "/can-activate", "rest\\v1\\UserController@canActivate");
        app()->router->get($base . "/logout", "rest\\v1\\UserController@logout");
        app()->router->get($base . "/current", "rest\\v1\\UserController@current");
        app()->router->post($base . "/login", "rest\\v1\\UserController@login");
        BaseRestController::routes($base, get_class(new self()));
    }

    public function save(Request $request) {
        return Service::user()->save($request->all());
    }

    public function get(string $id) {
        return Service::user()->get($id);
    }

    public function find(Request $req) {
        return Service::user()->find(Service::MakeFindQueryBuilder($req));
    }

    public function delete($id) {
        return Service::user()->delete($id);
    }

    public function login(Request $req) {
        $token = Service::user()->login($req->get('username'), $req->get('password'), $req->get('rememberMe'));
        return [ "token" => $token ];
    }

    public function logout() {
        return json_encode(Service::user()->logout());
    }

    public function current() {
        $user = Service::user()->currentUser();
        if (!$user) throw new NotAuthentificated("not authentificated");
        return $user;
    }

    public function rights() {
        return Service::user()->rights();
    }

    public function canActivate(Request $req) {
        $ref = $req->header("Referer");
        preg_match("~(http[s]?://[^:]+(:\d+)?)?(.*)~", $ref, $m);
        $path = $m[3];
        return json_encode(Service::menu()->canActivate($path));
    }
}
