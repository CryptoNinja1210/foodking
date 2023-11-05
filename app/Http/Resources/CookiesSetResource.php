<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CookiesSetResource extends JsonResource
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
    public function toArray($request)
    {
        return [
            "cookies_notification" => $this->info['cookies_notification'],
            "user_cookie_consent"  => $this->info['user_cookie_consent'],
        ];
    }
}
