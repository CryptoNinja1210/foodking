<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Models\Slider;
use App\Services\SliderService;
use App\Http\Requests\SliderRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\SliderResource;

class SliderController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private SliderService $sliderService;

    public function __construct(SliderService $slider)
    {
        parent::__construct();
        $this->sliderService = $slider;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy', 'show');
    }

    public function index(PaginateRequest $request)
    {
        try {
            return SliderResource::collection($this->sliderService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SliderRequest $request): SliderResource | \Illuminate\Http\Response
    {
        try {
            return new SliderResource($this->sliderService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider): SliderResource | \Illuminate\Http\Response
    {
        try {
            return new SliderResource($this->sliderService->show($slider));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SliderRequest $request, Slider $slider): SliderResource | \Illuminate\Http\Response
    {
        try {
            return new SliderResource($this->sliderService->update($request, $slider));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        try {
            $this->sliderService->destroy($slider);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}