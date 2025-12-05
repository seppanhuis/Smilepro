<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medewerker extends Model
{
    protected $table = 'medewerkers';

    protected $fillable = [
        'persoon_id',
        'nummer',
        'medewerker_type',
        'specialisatie',
        'is_actief',
        'opmerking',
    ];

    protected $casts = [
        'is_actief' => 'boolean',
    ];

    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Persoon::class);
    }

    public function getFullNameAttribute(): string
    {
        if (!$this->persoon) {
            return 'N/A';
        }

        $parts = array_filter([
            $this->persoon->voornaam,
            $this->persoon->tussenvoegsel,
            $this->persoon->achternaam
        ]);

        return implode(' ', $parts);
    }
}
