<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Offer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = "offers";
    protected $fillable = ['name', 'slug', 'amount', 'status', 'start_date', 'end_date'];
    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'slug'       => 'string',
        'amount'     => 'decimal:6',
        'status'     => 'integer',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'offer_items');
    }

    public function offerItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OfferItem::class, 'offer_id', 'id');
    }

    public function getCoverAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('offer'))) {
            $offer = $this->getMedia('offer')->last();
            return $offer->getUrl('cover');
        }
        return asset('images/default/offer.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('cover')->crop('crop-center', 548, 140)->keepOriginalImageFormat()->sharpen(10);
    }
}
