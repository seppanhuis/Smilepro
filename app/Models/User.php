<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'gebruikersnaam',
        'email',
        'password',
        'rol_naam',
        'ingelogd',
        'uitgelogd',
        'is_actief',
        'opmerking',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

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
            'ingelogd' => 'datetime',
            'uitgelogd' => 'datetime',
            'is_actief' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->gebruikersnaam)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the name attribute (alias for gebruikersnaam for compatibility)
     */
    public function getNameAttribute(): string
    {
        return $this->gebruikersnaam;
    }

    /**
     * Set the name attribute (alias for gebruikersnaam for compatibility)
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['gebruikersnaam'] = $value;
    }

    /**
     * Get the persoon record associated with this user
     */
    public function persoon(): HasOne
    {
        return $this->hasOne(Persoon::class, 'gebruiker_id');
    }
}
