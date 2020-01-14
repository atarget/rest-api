<?php

namespace App\Api\Service\Implementation;

use App\Api\Service\Contract\IMenuService;
use App\Api\Service\Service;
use App\Menu;

class MenuService extends AbstractService implements IMenuService
{
    public function save($entity, $query = null)
    {
        return parent::save($entity, Menu::query());
    }


    public function get($entity, $query = null)
    {
        return parent::get($entity, Menu::query());
    }


    public function find($findQueryBuilder, $query = null)
    {
        return parent::find($findQueryBuilder, Menu::query());
    }


    public function delete($entity, $query = null)
    {
        return parent::delete($entity, Menu::query());
    }


    public function makeEntity($data)
    {
        $entity = $this->makeEntityHelper($data, new Menu());
        return $entity;
    }

    public function canActivate($path) {
        $menu = Menu::query()->where("path", $path)->first();
        if (!$menu) return true;
        if ($menu->right === "*") return true;
        return Service::user()->can($menu->right);
    }


}
