<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Accessor: volledige naam van de medewerker
     */
    public function getFullNameAttribute(): string
    {
        if (!$this->persoon) {
            return 'N/A';
        }

        $parts = array_filter([
            $this->persoon->voornaam,
            $this->persoon->tussenvoegsel,
            $this->persoon->achternaam,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Relatie: medewerker hoort bij één persoon
     */
    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Persoon::class, 'persoon_id');
    }

    /**
     * Relatie: medewerker heeft meerdere afspraken
     */
    public function afspraken(): HasMany
    {
        return $this->hasMany(Afspraak::class, 'medewerker_id');
    }
}
