<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationAlert extends Model
{
    use HasFactory;
    protected $table = "notification_alerts";
    protected $fillable = ['name', 'language', 'mail_message', 'sms_message', 'push_notification_message', 'mail', 'sms', 'push_notification'];
    protected $casts = [
        'id'                        => 'integer',
        'name'                      => 'string',
        'language'                  => 'string',
        'mail_message'              => 'string',
        'sms_message'               => 'string',
        'push_notification_message' => 'string',
        'mail'                      => 'integer',
        'sms'                       => 'integer',
        'push_notification'         => 'integer',
    ];
}
