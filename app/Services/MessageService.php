<?php

namespace App\Services;

use App\Enums\Ask;
use Exception;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Arr;
use App\Models\MessageHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\PaginateRequest;

class MessageService
{
    public $message;

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'asc';
            return Message::with('messageHistory')->where(
                ['branch_id' => $request->branch_id, 'user_id' => $request->user_id]
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(MessageRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                if ($request->message_id) {
                    $this->message  = Message::find($request->message_id);
                    $messageHistory = MessageHistory::create(
                        Arr::except($request->validated(), ['branch_id']) + ['message_id' => $request->message_id]
                    );
                } else {
                    $this->message  = Message::create(
                        Arr::except(
                            $request->validated(),
                            ['user_id', 'text', 'is_read']
                        ) + ['user_id' => $request->receiver_id]
                    );
                    $messageHistory = MessageHistory::create(
                        Arr::except($request->validated(), ['branch_id']) + ['message_id' => $this->message->id]
                    );
                }
                if ($request->image) {
                    $messageHistory->addMediaFromRequest('image')->toMediaCollection('message');
                }
            });
            return $this->message;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function destroy(Message $message)
    {
        try {
            DB::transaction(function () use ($message) {
                MessageHistory::where('message_id', $message->id)->delete();
                $message->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Message $message): Message
    {
        try {
            return $message;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function changeStatus(Message $message, User $customer)
    {
        try {
            MessageHistory::where(['message_id' => $message->id, 'user_id' => $customer->id, 'is_read' => Ask::NO])->update(['is_read' => Ask::YES]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
