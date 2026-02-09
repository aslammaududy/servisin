<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplainPhoto extends Model
{
    protected $fillable = [
        'complaint_id',
        'path',
        'original_name',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
