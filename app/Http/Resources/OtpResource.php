<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

class OtpResource extends JsonResource
{

    public array $info;

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
    public function toArray($request): array
    {
        return [
            "otp_type"        => $this->info['otp_type'],
            "otp_digit_limit" => $this->info['otp_digit_limit'],
            "otp_expire_time" => $this->info['otp_expire_time'],
        ];
    }
}
