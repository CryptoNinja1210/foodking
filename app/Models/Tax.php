<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = "taxes";
    protected $fillable = ['name', 'code', 'tax_rate', 'type', 'status'];
    protected $casts = [
        'id'       => 'integer',
        'name'     => 'string',
        'code'     => 'string',
        'tax_rate' => 'string',
        'type'     => 'integer',
        'status'   => 'integer',
    ];
}
