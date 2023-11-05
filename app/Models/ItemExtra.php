<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemExtra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "item_extras";
    protected $fillable = ['item_id', 'name', 'status', 'price'];
    protected $casts = [
        'id'      => 'integer',
        'item_id' => 'integer',
        'name'    => 'string',
        'status'  => 'integer',
        'price'   => 'decimal:6',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
