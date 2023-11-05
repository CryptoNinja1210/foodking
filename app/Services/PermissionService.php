<?php

namespace App\Services;

use App\Http\Requests\PermissionRequest;
use App\Libraries\AppLibrary;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{

    /**
     * @throws Exception
     */
    public function permission(Role $role) : object
    {
        try {
            $permissions     = Permission::get();
            $rolePermissions = Permission::join(
                "role_has_permissions",
                "role_has_permissions.permission_id",
                "=",
                "permissions.id"
            )->where("role_has_permissions.role_id", $role->id)->get()->pluck('name', 'id');
            return AppLibrary::permissionWithAccess($permissions, $rolePermissions);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(PermissionRequest $request, Role $role) : Role
    {
        try {
            return $role->syncPermissions(Permission::whereIn('id', $request->get('permissions'))->get());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
