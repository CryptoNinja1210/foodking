<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'social_media_facebook'  => ['nullable', 'url', 'max:190'],
            'social_media_youtube'   => ['nullable', 'url', 'max:190'],
            'social_media_instagram' => ['nullable', 'url', 'max:190'],
            'social_media_twitter'   => ['nullable', 'url', 'max:190'],
        ];
    }
}
