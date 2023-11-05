<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "messages";
    protected $fillable = ['branch_id', 'user_id'];
    protected $casts = [
        'id'        => 'integer',
        'branch_id' => 'integer',
        'user_id'   => 'integer',
    ];


    public function messageHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MessageHistory::class, 'message_id', 'id')->orderBy('id', 'desc');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
