<?php

namespace App\Services;

use Exception;
use App\Enums\Ask;
use App\Models\User;
use App\Enums\Role as EnumRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\DeliveryBoyRequest;
use App\Http\Requests\UserChangePasswordRequest;


class DeliveryBoyService
{
    public $user;
    public $phoneFilter = ['phone'];
    public $roleFilter = ['role_id'];
    public $userFilter = ['name', 'email', 'username', 'branch_id', 'status', 'phone'];
    public $blockRoles = [EnumRole::ADMIN];


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

            return User::with('media', 'addresses')->role(EnumRole::DELIVERY_BOY)->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
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

    public function store(DeliveryBoyRequest $request)
    {
        try {
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

                $this->user->assignRole(EnumRole::DELIVERY_BOY);
            });
            return $this->user;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(DeliveryBoyRequest $request, User $deliveryBoy)
    {
        try {
            if (!in_array(EnumRole::DELIVERY_BOY, $this->blockRoles)) {
                DB::transaction(function () use ($deliveryBoy, $request) {
                    $this->user               = $deliveryBoy;
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
                return $this->user;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(User $deliveryBoy): User
    {
        try {
            if (!in_array(EnumRole::DELIVERY_BOY, $this->blockRoles)) {
                return $deliveryBoy;
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

    public function destroy(User $deliveryBoy)
    {
        try {
            if (!in_array(EnumRole::DELIVERY_BOY, $this->blockRoles)) {
                if ($deliveryBoy->hasRole(EnumRole::DELIVERY_BOY)) {
                    DB::transaction(function () use ($deliveryBoy) {
                        $deliveryBoy->addresses()->delete();
                        $deliveryBoy->delete();
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
    public function changePassword(UserChangePasswordRequest $request, User $deliveryBoy): User
    {
        try {
            if (!in_array(EnumRole::DELIVERY_BOY, $this->blockRoles)) {
                $deliveryBoy->password = Hash::make($request->password);
                $deliveryBoy->save();
                return $deliveryBoy;
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
    public function changeImage(ChangeImageRequest $request, User $deliveryBoy): User
    {
        try {
            if (!in_array(EnumRole::DELIVERY_BOY, $this->blockRoles)) {
                if ($request->image) {
                    $deliveryBoy->clearMediaCollection('profile');
                    $deliveryBoy->addMediaFromRequest('image')->toMediaCollection('profile');
                }
                return $deliveryBoy;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}