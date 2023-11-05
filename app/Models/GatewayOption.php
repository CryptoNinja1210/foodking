<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayOption extends Model
{

    protected $table = "gateway_options";
    protected $fillable = ['model_id', 'model_type', 'option', 'value', 'type', 'activities'];
    protected $casts = [
        'id'         => 'integer',
        'model_id'   => 'integer',
        'model_type' => 'string',
        'option'     => 'string',
        'value'      => 'string',
        'type'       => 'integer',
        'activities' => 'string',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
