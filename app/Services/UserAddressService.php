<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;

class UserAddressService
{
    /**
     * @throws Exception
     */
    public $address;
    public $addressFilter = ['label', 'address', 'apartment', 'latitude', 'longitude'];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Address::where('user_id', $user->id)->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->addressFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
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
    public function store($request, User $user): Address
    {
        try {
            DB::transaction(function () use ($request, $user) {
                $this->address = Address::create($request->validated() + ['user_id' => $user->id]);
            });
            return $this->address;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update($request, User $user, Address $address)
    {
        try {
            if ($user->id == $address->user_id) {
                return tap($address)->update($request->validated());
            } else {
                throw new Exception(trans('all.user_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(User $user, Address $address): void
    {
        try {
            if ($user->id == $address->user_id) {
                $address->delete();
            } else {
                throw new Exception(trans('all.user_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(User $user, Address $address): Address
    {
        try {
            if ($user->id == $address->user_id) {
                return $address;
            } else {
                throw new Exception(trans('all.user_match'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}