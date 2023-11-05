<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = "otps";
    protected $fillable = ['phone', 'code', 'token', 'created_at'];
    protected $casts = [
        'phone'      => 'string',
        'code'       => 'string',
        'token'      => 'string',
        'created_at' => 'datetime',
    ];
    public $timestamps = false;
}
