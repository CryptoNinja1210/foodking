<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SocialMediaRequest;
use App\Http\Resources\SocialMediaResource;
use App\Services\SocialMediaService;
use Exception;

class SocialMediaController extends AdminController
{
    private SocialMediaService $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService)
    {
        parent::__construct();
        $this->socialMediaService = $socialMediaService;

        $this->middleware(['permission:settings'])->only('update');
    }

    public function index(): \Illuminate\Http\Response | SocialMediaResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SocialMediaResource($this->socialMediaService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(SocialMediaRequest $request): \Illuminate\Http\Response | SocialMediaResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new SocialMediaResource($this->socialMediaService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
