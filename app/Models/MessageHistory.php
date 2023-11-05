<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MessageHistory extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = "message_histories";
    protected $fillable = ['message_id', 'user_id', 'text', 'is_read'];
    protected $casts = [
        'id'         => 'integer',
        'message_id' => 'integer',
        'user_id'    => 'integer',
        'text'       => 'string',
        'is_read'    => 'integer',
    ];

    public function getImageAttribute()
    {

        if (!empty($this->getFirstMediaUrl('message'))) {
            return asset($this->getFirstMediaUrl('message'));
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
