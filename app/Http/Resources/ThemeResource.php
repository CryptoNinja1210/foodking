<?php

namespace App\Http\Resources;


use App\Models\ThemeSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
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
            "theme_logo"         => $this->themeImage('theme_logo')->logo,
            "theme_favicon_logo" => $this->themeImage('theme_favicon_logo')->faviconLogo,
            "theme_footer_logo"  => $this->themeImage('theme_footer_logo')->footerLogo,
        ];
    }

    public function themeImage($key)
    {
        return ThemeSetting::where(['key' => $key])->first();
    }
}
