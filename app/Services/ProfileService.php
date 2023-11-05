<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;

class ProfileService
{

    /**
     * @param ProfileRequest $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws Exception
     */
    public function update(ProfileRequest $request)
    {
        try {
            $user               = User::find(auth()->user()->id);
            if (!blank($user)) {
                $user->name         = $request->first_name . ' ' . $request->last_name;
                $user->phone        = $request->get('phone');
                $user->email        = $request->get('email');
                $user->country_code = $request->get('country_code');
                $user->save();
            }

            return $user;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws Exception
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user           = User::find(auth()->user()->id);
            $user->password = bcrypt($request->get('password'));
            $user->save();
            return $user;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function changeImage(ChangeImageRequest $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            if ($request->image) {
                $user->clearMediaCollection('profile');
                $user->addMediaFromRequest('image')->toMediaCollection('profile');
            }
            $user->save();
            return $user;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}