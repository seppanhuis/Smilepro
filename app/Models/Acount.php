<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acount extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'gebruikersnaam',
        'email',
        'rol_naam',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ingelogd' => 'datetime',
        'uitgelogd' => 'datetime',
        'is_actief' => 'boolean',
    ];
}
