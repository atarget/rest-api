<?php

namespace App\Http\Controllers\rest\v1;

use App\Api\Service\Service;
use App\Http\Controllers\rest\BaseRestController;
use Illuminate\Http\Request;


class LangController extends BaseRestController {

    /**
     * @param string $base
     * @param null $controller
     */
    public static function routes(string $base, string $controller = null) {
        BaseRestController::routes($base, get_class(new self()));
    }

    public function save(Request $request) {
        return Service::lang()->save($request->all());
    }

    public function get(string $id) {
        return Service::lang()->get($id);
    }

    public function find(Request $req) {
        return Service::lang()->find(Service::MakeFindQueryBuilder($req));
    }

    public function delete($id) {
        return Service::lang()->delete($id);
    }

}
