<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class NotificationAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"                        => $this->id,
            "name"                      => $this->name,
            "language"                  => $this->language,
            "mail_message"              => $this->mail_message,
            "sms_message"               => $this->sms_message,
            "push_notification_message" => $this->push_notification_message,
            "mail"                      => $this->mail,
            'sms'                       => $this->sms,
            'push_notification'         => $this->push_notification
        ];
    }
}