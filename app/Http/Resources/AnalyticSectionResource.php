<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id'          => $this->id,
            'analytic_id' => $this->analytic_id,
            'name'        => $this->name,
            'section'     => $this->section,
            'data'        => $this->data,
        ];
    }
}
