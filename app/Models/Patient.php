<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $table = 'patienten';

    protected $fillable = [
        'persoon_id',
        'nummer',
        'medisch_dossier',
        'is_actief'
    ];

    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Persoon::class);
    }

    /**
     * Haal actieve patiënten op voor dropdown selectie
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getForDropdown()
    {
        return self::with('persoon')
            ->where('is_actief', true)
            ->get()
            ->map(function($patient) {
                $naam = $patient->persoon->voornaam . ' ';
                if ($patient->persoon->tussenvoegsel) {
                    $naam .= $patient->persoon->tussenvoegsel . ' ';
                }
                $naam .= $patient->persoon->achternaam;
                return [
                    'id' => $patient->id,
                    'naam' => $naam,
                ];
            });
    }
}
