<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transactions";
    protected $fillable = ['order_id', 'transaction_no', 'amount', 'payment_method', 'type', 'sign'];
    protected $casts = [
        'id'             => 'integer',
        'order_id'       => 'integer',
        'transaction_no' => 'string',
        'amount'         => 'decimal:6',
        'payment_method' => 'string',
        'type'           => 'string',
        'sign'           => 'string',
    ];

    public function order() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
