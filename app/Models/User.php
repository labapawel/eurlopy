<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'expired_at',
        'start_at',
        'end_at',
        'hours_per_week',

    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'start_at' => 'datetime',
        // 'hours_per_week' => 'array',
    ];
   

 public function setHoursPerWeekAttribute($value)
{
    \Log::info('MUTATOR - setHoursPerWeekAttribute', [
        'incoming_value' => $value,
        'type' => gettype($value)
    ]);
    
    if (is_array($value)) {
        $json = json_encode($value);
        \Log::info('MUTATOR - converting array to JSON', ['json' => $json]);
        $this->attributes['hours_per_week'] = $json;
    } else {
        $this->attributes['hours_per_week'] = $value;
    }
}

public function holyday()
{
    return $this->hasMany(Holyday::class);
}

public function getHoursPerWeekAttribute($value)
{
    \Log::info('ACCESSOR - getHoursPerWeekAttribute', [
        'raw_value' => $value,
        'type' => gettype($value)
    ]);
    
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        \Log::info('ACCESSOR - decoded value', ['decoded' => $decoded]);
        return $decoded ?? [];
    }
    
    return $value ?? [];
}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    function isAdmin(): bool
    {
        return ($this->attributes['role'] & 2) == 2;
    }   

    public function setPasswordAttribute($value): void
    {
        if (!empty($value) && bcrypt("") == $value) 
        {
            //  dd($value);
            $this->attributes['password'] =$value;
        }
    }

    /**
     * konwersja roli na tablicę
     *
     * @var array<string, string>
     */
    public function getRoleAttribute(): array
    {
        $role = $this->attributes['role'] ?? 0;
        $roles = [];
        for ($i = 0; $i < 32; $i++) {
            if ($role & (1 << $i)) {
                $roles[] = 1 << $i;
            }
        }
      //   dd($roles);
        return $roles;
        
    }
    /**
     * Set the user's password.
     *
     * @param  string  $value
     */
    public function setRoleAttribute($value): void
    {
        // dd($value);
        $this->attributes['role'] = (string) array_sum($value);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
