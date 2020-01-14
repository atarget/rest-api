<?php

namespace App\Http\Controllers\rest\v1;

use App\Api\Service\Service;
use App\Http\Controllers\rest\BaseRestController;
use App\Role;
use App\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TranslationController extends BaseRestController {

    /**
     * @param string $base
     * @param null $controller
     */
    public static function routes(string $base, string $controller = null) {
        app()->router->get($base . "/get", "rest\\v1\\TranslationController@translations");
        app()->router->post($base . "/missing", "rest\\v1\\TranslationController@missing");
        BaseRestController::routes($base, get_class(new self()));
    }

    public function save(Request $request) {
        return Service::trans()->save($request->all());
    }

    public function get(string $id) {
        return Service::trans()->get($id);
    }

    public function find(Request $req) {
        return Service::trans()->find(Service::MakeFindQueryBuilder($req));
    }

    public function delete($id) {
        return Service::trans()->delete($id);
    }

    public function translations(Request $req) {
        $lang = $req->header("x-lang", "uk");
        return Translation::query()->where("lang", $lang)
            ->whereNull("translatable_id")
            ->get();
    }

    public function missing(Request $req) {
        $text = $req->get("text");
        $lang = $req->header("x-lang", "uk");
        $trans = new Translation();
        $trans->text = $text;
        $trans->lang = $lang;
        $trans->translation = $text;
        $trans->save();
    }
}
