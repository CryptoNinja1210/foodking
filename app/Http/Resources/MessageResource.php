<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use App\Models\MessageHistory;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"         => $this->id,
            "branch_id"  => $this->branch_id,
            "user_id"    => $this->user_id,
            "user_name"  => optional($this->user)->name,
            "user_phone" => optional($this->user)->phone,
            "user_image" => optional($this->user)->image,
            "message"    => $this->messageHistory($this->messageHistory),
        ];
    }

    private function messageHistory($messageHistories)
    {
        $historyArray = [];
        foreach ($messageHistories as $messageHistory) {
            if ($messageHistory) {
                $history = MessageHistory::where('id', $messageHistory->id)->first();
                $historyArray[] = [
                    "id"         => $history->id,
                    "message_id" => $history->message_id,
                    "user_id"    => $history->user_id,
                    'user_name'  => $history->user->name,
                    'user_phone' => $history->user->phone,
                    'user_image' => $history->user->image,
                    "text"       => $history->text,
                    "image"      => $history->image,
                    "is_read"    => trans('ask.' . $history->is_read),
                    "reply_at"   => AppLibrary::datetime($history->created_at)
                ];
            }
        }
        return $historyArray;
    }
}
