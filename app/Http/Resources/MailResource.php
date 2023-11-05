<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MailResource extends JsonResource
{

    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            "mail_host"       => $this->info['mail_host'],
            "mail_port"       => $this->info['mail_port'],
            "mail_username"   => $this->info['mail_username'],
            "mail_password"   => $this->info['mail_password'],
            "mail_encryption" => $this->info['mail_encryption'],
            "mail_from_name"  => $this->info['mail_from_name'],
            "mail_from_email" => $this->info['mail_from_email']
        ];
    }

}
