<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Coupon extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = "coupons";

    protected $fillable = [
        'name',
        'description',
        'code',
        'start_date',
        'end_date',
        'discount',
        'discount_type',
        'minimum_order',
        'maximum_discount',
        'limit_per_user'
    ];
    protected $casts = [
        'id'               => 'integer',
        'name'             => 'string',
        'description'      => 'string',
        'code'             => 'string',
        'start_date'       => 'datetime',
        'end_date'         => 'datetime',
        'discount'         => 'decimal:6',
        'discount_type'    => 'integer',
        'minimum_order'    => 'decimal:6',
        'maximum_discount' => 'decimal:6',
        'limit_per_user'   => 'integer',
    ];

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('coupon'))) {
            return asset($this->getFirstMediaUrl('coupon'));
        }
        return asset('images/default/offer.png');
    }
}
