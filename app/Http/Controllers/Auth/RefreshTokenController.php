<?php

namespace App\Http\Controllers\Auth;


use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\TokenStoreRequest;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\JsonResponse;


class RefreshTokenController extends Controller
{
    public function refreshToken(TokenStoreRequest $request)
    {
        try {
            $sanctumToken = $request->token;
            $token = PersonalAccessToken::findToken($sanctumToken);
            $user = $token->tokenable;

            $token = $user->createToken('auth_token')->plainTextToken;

            return new JsonResponse([
                'token'      => $token,
            ], 201);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => trans('all.message.token_is_invalid')], 422);
        }
    }
}