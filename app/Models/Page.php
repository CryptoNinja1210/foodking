<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = "pages";
    protected $fillable = ['title', 'slug', 'description', 'menu_section_id', 'template_id', 'status'];
    protected $casts = [
        'id'              => 'integer',
        'title'           => 'string',
        'slug'            => 'string',
        'description'     => 'string',
        'menu_section_id' => 'integer',
        'template_id'     => 'integer',
        'status'          => 'integer',
    ];

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('page-image'))) {
            return asset($this->getFirstMediaUrl('page-image'));
        }
        return '';
    }

    public function menuSection()
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id', 'id');
    }
}
