<?php

namespace App\Services;

use App\Libraries\AppLibrary;
use App\Models\Menu;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuService
{
    /**
     * @throws Exception
     */
    public function menu(Role $role) : array
    {
        try {
            $menus           = Menu::get()->toArray();
            $permissions     = Permission::get();
            $rolePermissions = Permission::join(
                "role_has_permissions",
                "role_has_permissions.permission_id",
                "=",
                "permissions.id"
            )->where("role_has_permissions.role_id", $role->id)->get()->pluck('name', 'id');
            $permissions     = AppLibrary::permissionWithAccess($permissions, $rolePermissions);
            $permissions     = AppLibrary::pluck($permissions, 'obj', 'url');
            return AppLibrary::numericToAssociativeArrayBuilder(AppLibrary::menu($menus, $permissions));
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
