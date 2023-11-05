<?php

namespace App\Services;

use App\Enums\Ask;
use App\Enums\Role as EnumRole;
use App\Http\Requests\AdministratorRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Libraries\AppLibrary;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdministratorService
{
    public $user;
    public $userFilter = ['name', 'email', 'username', 'branch_id', 'status'];

    /**a
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

            return User::with('media')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->userFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->role(EnumRole::ADMIN)->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(AdministratorRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->user = User::create([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'phone'             => $request->phone,
                    'username'          => AppLibrary::username($request->name),
                    'password'          => Hash::make($request->password),
                    'status'            => $request->status,
                    'email_verified_at' => now(),
                    'branch_id'         => $request->branch_id,
                    'country_code'      => $request->country_code,
                    'is_guest'          => Ask::NO,
                ]);
                $this->user->assignRole(EnumRole::ADMIN);
            });
            return $this->user;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(AdministratorRequest $request, User $administrator)
    {
        try {
            DB::transaction(function () use ($administrator, $request) {
                $this->user               = $administrator;
                $this->user->name         = $request->name;
                $this->user->email        = $request->email;
                $this->user->phone        = $request->phone;
                $this->user->status       = $request->status;
                $this->user->branch_id    = $request->branch_id;
                $this->user->country_code = $request->country_code;

                if ($request->password) {
                    $this->user->password = Hash::make($request->password);
                }
                $this->user->save();
            });
            return $this->user;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(User $administrator)
    {
        try {
            if (Auth::user()->id != $administrator->id && $administrator->id != 1) {
                if ($administrator->hasRole(EnumRole::ADMIN)) {
                    DB::transaction(function () use ($administrator) {
                        $administrator->removeRole($administrator->roles[0]->id);
                        $administrator->addresses()->delete();
                        $administrator->delete();
                    });
                } else {
                    throw new Exception(trans('The permission is denied.'), 422);
                }
            } else {
                throw new Exception(trans('The permission is denied.'), 422);
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
    public function show(User $administrator): User
    {
        try {
            if ($administrator->hasRole(EnumRole::ADMIN)) {
                return $administrator;
            } else {
                throw new Exception(trans('The permission is denied.'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changePassword(UserChangePasswordRequest $request, User $administrator): User
    {
        try {
            if ($administrator->hasRole(EnumRole::ADMIN)) {
                $administrator->password = Hash::make($request->password);
                $administrator->save();
                return $administrator;
            } else {
                throw new Exception(trans('The permission is denied.'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, User $administrator): User
    {
        try {
            if ($administrator->hasRole(EnumRole::ADMIN)) {
                $administrator->clearMediaCollection('profile');
                $administrator->addMediaFromRequest('image')->toMediaCollection('profile');
                return $administrator;
            } else {
                throw new Exception(trans('The permission is denied.'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}