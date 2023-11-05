<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CapturePaymentNotification extends Model
{
    public $timestamps = false;
    protected $table = "capture_payment_notifications";
    protected $fillable = ['order_id', 'token', 'created_at'];
    protected $casts = [
        'order_id'   => 'integer',
        'token'      => 'string',
        'created_at' => 'datetime',
    ];
}
