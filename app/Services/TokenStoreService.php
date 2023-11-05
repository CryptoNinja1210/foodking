<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TokenStoreRequest;

class TokenStoreService
{

    /**
     * @throws Exception
     */
    public function webToken(TokenStoreRequest $request)
    {
        try {

            $user = User::find(auth()->user()->id);
            $user->web_token = $request->token;
            $user->save();

            return true;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
    /**
     * @throws Exception
     */
    public function deviceToken(TokenStoreRequest $request)
    {
        try {

            $user = User::find(auth()->user()->id);
            $user->device_token = $request->token;
            $user->save();

            return true;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}