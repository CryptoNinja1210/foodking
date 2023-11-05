<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DefaultAccessResource extends JsonResource
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
            "branch_id" => $this->info['branch_id']
        ];
    }

}
