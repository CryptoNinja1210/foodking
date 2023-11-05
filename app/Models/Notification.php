<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = "notifications";
    protected $fillable    = ['model_type', 'model_id', 'data', 'read_at'];
    protected $casts = [
        'id'         => 'integer',
        'model_type' => 'string',
        'model_id'   => 'integer',
        'data'       => 'string',
        'read_at'    => 'datetime',
    ];
}
