<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model voor behandelingen
 *
 * Representeert een tandheelkundige behandeling in het systeem.
 * Bevat informatie over de medewerker, patiënt, datum, tijd, type en status.
 */
class Behandeling extends Model
{
    /**
     * De tabel die bij dit model hoort
     *
     * @var string
     */
    protected $table = 'behandelingen';

    /**
     * Kolommen die mass-assignable zijn
     *
     * @var array<string>
     */
    protected $fillable = [
        'medewerker_id',
        'patient_id',
        'datum',
        'tijd',
        'behandeling_type',
        'omschrijving',
        'kosten',
        'status',
        'is_actief',
        'opmerking',
    ];

    /**
     * Type casting voor attributen
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datum' => 'date',
        'kosten' => 'decimal:2',
        'is_actief' => 'boolean',
    ];

    /**
     * Relatie met de medewerker
     *
     * Elke behandeling wordt uitgevoerd door één medewerker
     *
     * @return BelongsTo
     */
    public function medewerker(): BelongsTo
    {
        return $this->belongsTo(Medewerker::class);
    }

    /**
     * Relatie met de patiënt
     *
     * Elke behandeling is voor één patiënt
     *
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Haal actieve behandelingen op voor dropdown selectie
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getForDropdown()
    {
        return self::with(['patient.persoon'])
            ->where('is_actief', true)
            ->get()
            ->map(function($behandeling) {
                $naam = $behandeling->patient->persoon->voornaam . ' ';
                if ($behandeling->patient->persoon->tussenvoegsel) {
                    $naam .= $behandeling->patient->persoon->tussenvoegsel . ' ';
                }
                $naam .= $behandeling->patient->persoon->achternaam;
                return [
                    'id' => $behandeling->id,
                    'label' => $naam . ' - ' . $behandeling->behandeling_type . ' (' . $behandeling->datum->format('d-m-Y') . ')',
                    'patient_id' => $behandeling->patient_id,
                ];
            });
    }
}
