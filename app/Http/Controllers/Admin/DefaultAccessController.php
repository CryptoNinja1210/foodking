<?php

namespace App\Http\Controllers\Admin;


use App\Http\Resources\DefaultAccessResource;
use App\Services\DefaultAccessService;
use Exception;
use Illuminate\Http\Request;


class DefaultAccessController extends AdminController
{
    private DefaultAccessService $defaultAccessService;

    public function __construct( DefaultAccessService $defaultAccessService )
    {
        parent::__construct();
        $this->defaultAccessService = $defaultAccessService;
    }

    public function index() : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | DefaultAccessResource | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new DefaultAccessResource($this->defaultAccessService->show());
        } catch( Exception $exception ) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function storeOrUpdate( Request $request ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | DefaultAccessResource | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new DefaultAccessResource($this->defaultAccessService->storeOrUpdate($request->all()));
        } catch( Exception $exception ) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
