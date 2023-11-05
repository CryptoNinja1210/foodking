<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

class CountryCodeResource extends JsonResource
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
            "calling_code"  => $this->calling_codes[0] == '+1201' ? '+1' : $this->calling_codes[0],
            "flag_emoji"    => $this->flag->emoji,
            "flag_svg"      => $this->flag->svg,
            "flag_svg_path" => $this->flag->svg_path,
            "capital"       => $this->capital_rinvex,
            "nationality"   => $this->demonym,
        ];
    }
}
