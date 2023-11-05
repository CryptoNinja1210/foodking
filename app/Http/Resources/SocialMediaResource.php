<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
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
            "social_media_facebook"  => $this->info['social_media_facebook'],
            "social_media_instagram" => $this->info['social_media_instagram'],
            "social_media_twitter"   => $this->info['social_media_twitter'],
            "social_media_youtube"   => $this->info['social_media_youtube']

        ];
    }
}
