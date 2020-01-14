<?php


namespace App\Api\Service;


class Helper {
    /**
     * @return  \Illuminate\Http\Request
     */
    public static function request() {
        return app()->get("\Illuminate\Http\Request");
    }
}
