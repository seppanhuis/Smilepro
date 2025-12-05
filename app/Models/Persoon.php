<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persoon extends Model
{
    protected $table = 'personen';

    protected $fillable = [
        'gebruiker_id',
        'voornaam',
        'tussenvoegsel',
        'achternaam',
        'geboortedatum',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'geboortedatum' => 'date',
        'is_actief' => 'boolean',
    ];

    public function gebruiker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gebruiker_id');
    }

    public function medewerker(): HasOne
    {
        return $this->hasOne(Medewerker::class);
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->voornaam,
            $this->tussenvoegsel,
            $this->achternaam
        ]);

        return implode(' ', $parts);
    }
}
