<?php


namespace App\Api\Service;


use App\Api\Service\Contract\IFindQueryBuilder;
use App\Api\Service\Contract\ILangService;
use App\Api\Service\Contract\ITranslationService;
use App\Api\Service\Contract\IUserService;
use App\Api\Service\Contract\IMenuService;
/**{{{use-anchor}}}**/
use Illuminate\Http\Request;

class Service {

    static $userService = null;
    /**
     * @return IUserService
     */
    public static function user() {
        if (self::$userService) return self::$userService;
        self::$userService = app()->get("service.user");
        return self::$userService;
    }

    static $translationService = null;
    /**
     * @return ITranslationService
     */
    public static function trans() {
        if (self::$translationService) return self::$translationService;
        self::$translationService = app()->get("service.translation");
        return self::$translationService;
    }

    static $langService = null;
    /**
     * @return ILangService
     */
    public static function lang() {
        if (self::$langService) return self::$langService;
        self::$langService = app()->get("service.lang");
        return self::$langService;
    }

    static $menuService = null;
    /**
     * @return IMenuService
     */
    public static function menu() {
        if (self::$menuService) return self::$menuService;
        self::$menuService = app()->get("service.menu");
        return self::$menuService;
    }
    /**{{{service-getter-anchor}}}**/

    /**
     * @param Request $req
     * @return mixed
     */
    public static function MakeFindQueryBuilder(Request $req) {
        $builder = app()->get("FindQueryBuilder");
        /** @var IFindQueryBuilder $builder */
        return $builder->newBuilder($req);
    }
}
