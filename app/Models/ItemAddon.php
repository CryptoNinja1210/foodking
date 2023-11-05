<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemAddon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "item_addons";
    protected $fillable = ['item_id', 'addon_item_id', 'addon_item_variation'];
    protected $casts = [
        'id'                   => 'integer',
        'item_id'              => 'integer',
        'addon_item_id'        => 'integer',
        'addon_item_variation' => 'string',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    public function addonItem()
    {
        return $this->belongsTo(Item::class, 'addon_item_id', 'id');
    }
}
