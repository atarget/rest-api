<?php

namespace App\Http\Controllers\rest\v1;

use App\Api\Service\Service;
use App\Http\Controllers\rest\BaseRestController;
use Illuminate\Http\Request;

class MenuController extends BaseRestController
{
	public static function routes(string $base, string $controller = null)
	{
		BaseRestController::routes($base, get_class(new self()));
	}


	public function save(Request $request)
	{
		return Service::menu()->save($request->all());
	}


	public function get(string $id)
	{
		return Service::menu()->get($id);
	}


	public function find(Request $request)
	{
		return Service::menu()->find(Service::MakeFindQueryBuilder($req));
	}


	public function delete(string $id)
	{
		return Service::menu()->delete($id);
	}


}
