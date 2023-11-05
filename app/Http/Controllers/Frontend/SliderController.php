<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\SliderResource;
use Exception;
use App\Services\SliderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;

class SliderController extends Controller
{
    private SliderService $sliderService;

    public function __construct(SliderService $slider)
    {
        $this->sliderService = $slider;
    }

    public function index(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return SliderResource::collection($this->sliderService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
