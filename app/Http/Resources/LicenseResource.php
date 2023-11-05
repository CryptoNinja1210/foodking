<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class LicenseResource extends JsonResource
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
    public function toArray($request) : array
    {
        return [
            "license_key" => $this->info['license_key']
        ];
    }

}
