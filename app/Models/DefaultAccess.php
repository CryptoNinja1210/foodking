<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultAccess extends Model
{
    use HasFactory;

    protected $table = "default_access";
    protected $fillable = ['name', 'user_id', 'default_id'];

    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'user_id'    => 'integer',
        'default_id' => 'integer',
    ];
}
