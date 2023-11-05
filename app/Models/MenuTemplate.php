<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuTemplate extends Model
{
    use HasFactory;

    protected $table = "menu_templates";
    protected $fillable = ['name'];
    protected $casts = [
        'id'   => 'integer',
        'name' => 'string',
    ];
}
