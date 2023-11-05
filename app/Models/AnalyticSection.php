<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticSection extends Model
{
    use HasFactory;

    protected $table = "analytic_sections";
    protected $fillable = ['analytic_id', 'name', 'data', 'section'];
    protected $casts = [
        'id'          => 'integer',
        'analytic_id' => 'integer',
        'name'        => 'string',
        'data'        => 'string',
        'section'     => 'integer',
    ];

    public function analytic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Analytic::class);
    }
}
