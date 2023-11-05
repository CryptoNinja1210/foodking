<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Models\Role;

class PushNotification extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = "push_notifications";
    protected $fillable = ['role_id', 'user_id', 'branch_id', 'title', 'description'];
    protected $casts = [
        'id'          => 'integer',
        'role_id'     => 'integer',
        'user_id'     => 'integer',
        'branch_id'   => 'integer',
        'title'       => 'string',
        'description' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function getImageAttribute()
    {
        if (!empty($this->getFirstMediaUrl('pushNotifications'))) {
            return asset($this->getFirstMediaUrl('pushNotifications'));
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
