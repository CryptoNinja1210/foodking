<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = "time_slots";
    protected $fillable = ['opening_time', 'closing_time', 'day'];
    protected $casts = [
        'id'           => 'integer',
        'opening_time' => 'string',
        'closing_time' => 'string',
        'day'          => 'integer',
    ];
}
