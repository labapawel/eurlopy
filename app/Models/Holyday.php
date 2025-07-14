<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holyday extends Model
{
    protected $fillable = [
        'user_id',
        'holyday_type_id',
        'start_date',
        'end_date',
        'hours',
        'description',
        'approved',
        'paid',
        'active',
    ];

    public function user()
        {
            return $this->belongsTo(User::class);
        }
    public function holydaytype()
        {
            return $this->belongsTo(HolydayType::class, 'holyday_type_id');
        }
}
