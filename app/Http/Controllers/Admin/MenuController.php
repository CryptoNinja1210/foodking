<?php

namespace App\Http\Controllers\Admin;


use App\Services\MenuService;
use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class MenuController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Role $role) : \Illuminate\Http\Response | JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $menus = app(MenuService::class)->menu($role);
            return new JsonResponse(['data' => $menus], 201);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

}
