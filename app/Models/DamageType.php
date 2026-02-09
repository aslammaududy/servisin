<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamageType extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
        'is_active',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected function casts()
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
