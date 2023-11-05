<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    use HasFactory;

    protected $table = "menu_sections";
    protected $fillable = ['name'];
    protected $casts = [
        'id'   => 'integer',
        'name' => 'string',
    ];
}
