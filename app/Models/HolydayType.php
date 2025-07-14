<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolydayType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active',
        'is_paid',
        'hours',
        'color',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_paid' => 'boolean',
        'hours' => 'integer',
    ];


    public function holyday()
    {
        return $this->hasMany(Holyday::class);
    }

    public function getColorAttribute($value)
    {
        return $value ?: '#000000'; // Default color if not set
    }
}
