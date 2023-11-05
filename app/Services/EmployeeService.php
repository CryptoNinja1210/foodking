<?php

namespace App\Services;

use Exception;
use App\Enums\Ask;
use App\Models\User;
use App\Enums\Role as EnumRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\UserChangePasswordRequest;


class EmployeeService
{
    public $user;
    public $phoneFilter = ['phone'];
    public $roleFilter = ['role_id'];
    public $userFilter = ['name', 'email', 'username', 'branch_id', 'status', 'phone'];
    public $blockRoles = [EnumRole::ADMIN, EnumRole::CUSTOMER, EnumRole::DELIVERY_BOY, EnumRole::WAITER, EnumRole::CHEF];


    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return User::with('media', 'addresses', 'roles')->where(
                function ($query) use ($requests) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('id', '!=', EnumRole::ADMIN);
                        $query->where('id', '!=', EnumRole::CUSTOMER);
                        $query->where('id', '!=', EnumRole::DELIVERY_BOY);
                        $query->where('id', '!=', EnumRole::WAITER);
                        $query->where('id', '!=', EnumRole::CHEF);
                    });
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->roleFilter)) {
                            $query->whereHas('roles', function ($query) use ($request, $key) {
                                $query->where('id', '=', $request);
                            });
                        }
                        if (in_array($key, $this->userFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function store(EmployeeRequest $request)
    {
        try {
            if (!in_array($request->role_id, $this->blockRoles)) {
                DB::transaction(function () use ($request) {
                    $this->user = User::create([
                        'name'              => $request->name,
                        'email'             => $request->email,
                        'phone'             => $request->phone,
                        'username'          => $this->username($request->email),
                        'password'          => bcrypt($request->password),
                        'branch_id'         => $request->branch_id,
                        'status'            => $request->status,
                        'email_verified_at' => now(),
                        'country_code'      => $request->country_code,
                        'is_guest'          => Ask::NO,
                    ]);

                    $this->user->assignRole($request->role_id);
                });
                return $this->user;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(EmployeeRequest $request, User $employee)
    {
        try {
            if (!in_array($request->role_id, $this->blockRoles) && !in_array(
                optional($employee->roles[0])->id,
                $this->blockRoles
            )) {
                DB::transaction(function () use ($employee, $request) {
                    $this->user               = $employee;
                    $this->user->name         = $request->name;
                    $this->user->email        = $request->email;
                    $this->user->phone        = $request->phone;
                    $this->user->branch_id    = $request->branch_id;
                    $this->user->status       = $request->status;
                    $this->user->country_code = $request->country_code;
                    if ($request->password) {
                        $this->user->password = Hash::make($request->password);
                    }
                    $this->user->save();
                });
                $this->user->syncRoles($request->role_id);
                return $this->user;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(User $employee): User
    {
        try {
            if (!in_array(optional($employee->roles[0])->id, $this->blockRoles)) {
                return $employee;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */

    public function destroy(User $employee)
    {
        try {
            if (!in_array(optional($employee->roles[0])->id, $this->blockRoles)) {
                if ($employee->hasRole(optional($employee->roles[0])->id)) {
                    DB::transaction(function () use ($employee) {
                        $employee->addresses()->delete();
                        $employee->delete();
                    });
                } else {
                    throw new Exception(trans('all.message.permission_denied'), 422);
                }
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    private function username($email): string
    {
        $emails = explode('@', $email);
        return $emails[0] . mt_rand();
    }

    /**
     * @throws Exception
     */
    public function changePassword(UserChangePasswordRequest $request, User $employee): User
    {
        try {
            if (!in_array(optional($employee->roles[0])->id, $this->blockRoles)) {
                $employee->password = Hash::make($request->password);
                $employee->save();
                return $employee;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, User $employee): User
    {
        try {
            if (!in_array(optional($employee->roles[0])->id, $this->blockRoles)) {
                if ($request->image) {
                    $employee->clearMediaCollection('profile');
                    $employee->addMediaFromRequest('image')->toMediaCollection('profile');
                }
                return $employee;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}