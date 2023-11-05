<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Services\SimpleUserService;
use App\Http\Resources\SimpleUserResource;
use App\Http\Requests\PaginateRequest;

class SimpleUserController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private SimpleUserService $simpleUserService;

    public function __construct(SimpleUserService $simpleUserService)
    {
        parent::__construct();
        $this->simpleUserService = $simpleUserService;
    }

    public function index(PaginateRequest $request)
    {
        try {
            return SimpleUserResource::collection($this->simpleUserService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
