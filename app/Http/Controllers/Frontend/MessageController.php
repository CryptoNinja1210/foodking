<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Message;
use App\Services\MessageService;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\MessageResource;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(PaginateRequest $request)
    {
        try {
            return MessageResource::collection($this->messageService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(Message $message)
    {
        try {
            return new MessageResource($this->messageService->show($message));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(MessageRequest $request): \Illuminate\Http\Response | MessageResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new MessageResource($this->messageService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Message $message)
    {
        try {
            $this->messageService->destroy($message);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}