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
    ];
   

    /**
     * Set the user's hours per week.
     *
     * @param  mixed  $value
     */
    public function setHoursPerWeekAttribute($value): void
    {
        // dd($value);
        $this->attributes['hours_per_week'] = $value;
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
        // dd($roles);
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
