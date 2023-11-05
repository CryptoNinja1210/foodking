<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CookiesResource extends JsonResource
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
    public function toArray($request): array
    {
        return [
            "cookies_details_page_id" => $this->info['cookies_details_page_id'],
            "cookies_summary" => $this->info['cookies_summary'],
        ];
    }
}
