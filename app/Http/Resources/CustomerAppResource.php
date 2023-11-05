<?php

namespace App\Http\Resources;


use App\Models\CustomerAppSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAppResource extends JsonResource
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
            "customer_app_name"               => $this->info['customer_app_name'],
            "customer_app_logo"               => $this->appImage('customer_app_logo')->logo,
            "customer_app_splash_screen_logo" => $this->appImage('customer_app_splash_screen_logo')->splashScreenLogo,
        ];
    }

    public function appImage($key)
    {
        return CustomerAppSetting::where(['key' => $key])->first();
    }
}
