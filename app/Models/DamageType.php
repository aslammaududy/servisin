<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageType extends Model
{
    use HasFactory;

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
