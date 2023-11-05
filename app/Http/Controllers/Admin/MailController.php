<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MailRequest;
use App\Http\Resources\MailResource;
use App\Services\MailService;
use Exception;

class MailController extends AdminController
{
    private MailService $mailService;

    public function __construct(MailService $mailService)
    {
        parent::__construct();
        $this->mailService = $mailService;
        $this->middleware(['permission:settings'])->only('update');
    }

    public function index() : \Illuminate\Http\Response | MailResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new MailResource($this->mailService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(MailRequest $request) : \Illuminate\Http\Response | MailResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new MailResource($this->mailService->update($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
