<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dayoff extends Model
{
    protected $fillable = [
        'name',
        'date',
        'active',
    ];

    protected $casts = [
        'date' => 'date',
        'active' => 'boolean',
    ];

    public function getActiveAttribute($value)
    {
        return (bool) $value;
    }
}
