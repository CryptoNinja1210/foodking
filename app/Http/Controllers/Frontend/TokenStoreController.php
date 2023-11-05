<?php

namespace App\Http\Controllers\Frontend;


use Exception;
use App\Services\TokenStoreService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TokenStoreRequest;


class TokenStoreController extends Controller
{
    private TokenStoreService $tokenStoreService;

    public function __construct(TokenStoreService $tokenStoreService)
    {
        $this->tokenStoreService = $tokenStoreService;
    }

    public function webToken(TokenStoreRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->tokenStoreService->webToken($request);
            return response(['status' => true, 'message' => trans("all.message.token_save")]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function deviceToken(TokenStoreRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->tokenStoreService->deviceToken($request);
            return response(['status' => true, 'message' => trans("all.message.token_save")]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
